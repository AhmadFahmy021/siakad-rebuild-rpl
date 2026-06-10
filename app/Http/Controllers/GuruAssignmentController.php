<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Tugas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\MataPelajaran;
use App\Models\PengumpulanTugas;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GuruAssignmentController extends Controller
{
    private function getGuruOrAbort()
    {
        $guru = Guru::where('user_id', Auth::id())->first();
        if (!$guru) {
            abort(403, 'Aksi tidak diizinkan.');
        }
        return $guru;
    }

    private function getClassesAndSubjects($guru)
    {
        // Get unique kelas_id from schedules
        $kelasIdsFromJadwal = Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->toArray();
        $myKelasIds = array_unique($kelasIdsFromJadwal);

        $classes = Kelas::whereIn('id', $myKelasIds)->get();
        if ($classes->isEmpty()) {
            $classes = Kelas::all();
        }

        $subjectIds = Jadwal::where('guru_id', $guru->id)->pluck('mata_pelajaran_id')->toArray();
        $subjects = MataPelajaran::whereIn('id', $subjectIds)->get();
        if ($subjects->isEmpty()) {
            $subjects = MataPelajaran::all();
        }

        return [$classes, $subjects, $myKelasIds];
    }

    public function index(Request $request)
    {
        $guru = $this->getGuruOrAbort();
        list($classes, $subjects, $myKelasIds) = $this->getClassesAndSubjects($guru);
        $hasSchedule = Jadwal::where('guru_id', $guru->id)->exists();

        // Stats calculation
        $allTeacherTasks = Tugas::where('guru_id', $guru->id)->get();
        $allTeacherTaskIds = $allTeacherTasks->pluck('id')->toArray();

        $totalActive = Tugas::where('guru_id', $guru->id)
            ->where('status', 'PUBLISHED')
            ->where('due_date', '>', Carbon::now())
            ->count();

        $totalCompleted = Tugas::where('guru_id', $guru->id)
            ->where(function($q) {
                $q->where('status', 'COMPLETED')
                  ->orWhere('due_date', '<=', Carbon::now());
            })->count();

        $needGrading = PengumpulanTugas::whereIn('tugas_id', $allTeacherTaskIds)
            ->where('status', 'sudah_mengumpulkan')
            ->count();

        $publishedTasks = $allTeacherTasks->where('status', 'PUBLISHED');
        $totalPossibleSubmissions = 0;
        $actualSubmissions = 0;
        foreach ($publishedTasks as $task) {
            $studentCount = Siswa::whereHas('kelas', fn($q) => $q->where('kelas.id', $task->kelas_id))->count();
            $totalPossibleSubmissions += $studentCount;
            $actualSubmissions += $task->pengumpulanTugas()
                ->whereIn('status', ['sudah_mengumpulkan', 'dinilai'])
                ->count();
        }
        $avgSubmissionRate = $totalPossibleSubmissions > 0 ? round(($actualSubmissions / $totalPossibleSubmissions) * 100) : 0;

        // Query assignments
        $query = Tugas::where('guru_id', $guru->id)
            ->with(['kelas', 'matapelajaran', 'pengumpulanTugas']);

        if ($request->filled('class_id')) {
            $query->where('kelas_id', $request->class_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('guru.assignment', compact(
            'assignments',
            'classes',
            'totalActive',
            'totalCompleted',
            'needGrading',
            'avgSubmissionRate',
            'hasSchedule'
        ));
    }

    public function create()
    {
        $guru = $this->getGuruOrAbort();
        
        if (!Jadwal::where('guru_id', $guru->id)->exists()) {
            return redirect()->route('assignment.index')->with('error', 'Anda belum memiliki jadwal mengajar (Mata Pelajaran/Kelas) sehingga tidak dapat membuat tugas.');
        }

        list($classes, $subjects) = $this->getClassesAndSubjects($guru);

        return view('guru.assignment.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        $guru = $this->getGuruOrAbort();
        
        if (!Jadwal::where('guru_id', $guru->id)->exists()) {
            return redirect()->route('assignment.index')->with('error', 'Aksi ditolak. Anda belum memiliki jadwal mengajar.');
        }

        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'matapelajaran_id' => 'required|uuid|exists:mata_pelajaran,id',
            'tipe' => 'required|string|max:50',
            'max_score' => 'required|integer|min:0|max:1000',
            'kkm' => 'required|integer|min:0|max:1000',
            'kelas_ids' => 'required|array|min:1',
            'kelas_ids.*' => 'uuid|exists:kelas,id',
            'due_date' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,zip,rar,doc,docx,png,jpg,jpeg|max:25600',
            'link' => 'nullable|url|max:255',
            'status' => 'required|string|in:DRAFT,PUBLISHED',
        ]);

        $filePath = null;
        if ($request->hasFile('file_tugas')) {
            $file = $request->file('file_tugas');
            $filename = time() . '_tugas_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('tugas_file', $filename, 'public');
        }

        foreach ($request->kelas_ids as $kelasId) {
            Tugas::create([
                'kelas_id' => $kelasId,
                'guru_id' => $guru->id,
                'matapelajaran_id' => $request->matapelajaran_id,
                'title' => $request->title,
                'description' => $request->description,
                'tipe' => $request->tipe,
                'max_score' => $request->max_score,
                'kkm' => $request->kkm,
                'due_date' => $request->due_date,
                'file_path' => $filePath,
                'link' => $request->link,
                'status' => $request->status,
            ]);
        }

        return redirect()->route('assignment.index')->with('success', 'Tugas berhasil dibuat.');
    }

    public function edit($id)
    {
        $guru = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);
        list($classes, $subjects) = $this->getClassesAndSubjects($guru);

        return view('guru.assignment.edit', compact('tugas', 'classes', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        $guru = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'matapelajaran_id' => 'required|uuid|exists:mata_pelajaran,id',
            'tipe' => 'required|string|max:50',
            'max_score' => 'required|integer|min:0|max:1000',
            'kkm' => 'required|integer|min:0|max:1000',
            'kelas_id' => 'required|uuid|exists:kelas,id',
            'due_date' => 'required|date',
            'file_tugas' => 'nullable|file|mimes:pdf,zip,rar,doc,docx,png,jpg,jpeg|max:25600',
            'link' => 'nullable|url|max:255',
            'status' => 'required|string|in:DRAFT,PUBLISHED,COMPLETED',
        ]);

        $filePath = $tugas->file_path;
        if ($request->hasFile('file_tugas')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $file = $request->file('file_tugas');
            $filename = time() . '_tugas_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $filePath = $file->storeAs('tugas_file', $filename, 'public');
        }

        $tugas->update([
            'kelas_id' => $request->kelas_id,
            'matapelajaran_id' => $request->matapelajaran_id,
            'title' => $request->title,
            'description' => $request->description,
            'tipe' => $request->tipe,
            'max_score' => $request->max_score,
            'kkm' => $request->kkm,
            'due_date' => $request->due_date,
            'file_path' => $filePath,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return redirect()->route('assignment.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $guru = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);

        if ($tugas->file_path) {
            Storage::disk('public')->delete($tugas->file_path);
        }
        $tugas->delete();

        return redirect()->route('assignment.index')->with('success', 'Tugas berhasil dihapus.');
    }

    public function gradeList($id, Request $request)
    {
        $guru = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)->with(['kelas', 'matapelajaran'])->findOrFail($id);

        $students = Siswa::whereHas('kelas', fn($q) => $q->where('kelas.id', $tugas->kelas_id))->with('user')->get();
        $submissions = PengumpulanTugas::where('tugas_id', $tugas->id)->get()->keyBy('siswa_id');

        $dueLimit = Carbon::parse($tugas->due_date);

        // Process student records
        $studentsGradingList = [];
        $turnedInCount = 0;
        $gradedCount = 0;
        $lateCount = 0;
        $totalScores = 0;

        foreach ($students as $student) {
            $submission = $submissions->get($student->id);
            $status = 'MISSING';
            $file = null;
            $link = null;
            $catatan = null;
            $jawaban_teks = null;
            $nilaiVal = null;
            $submittedAt = null;

            if ($submission) {
                $status = 'SUBMITTED';
                $file = $submission->file_path;
                $link = $submission->link;
                $catatan = $submission->catatan;
                $jawaban_teks = $submission->jawaban_teks;
                $nilaiVal = $submission->nilai;
                $submittedAt = $submission->created_at;

                if ($submission->status === 'dinilai') {
                    $status = 'GRADED';
                    $gradedCount++;
                    if ($nilaiVal !== null) {
                        $totalScores += $nilaiVal;
                    }
                } else {
                    $turnedInCount++;
                }

                // Check if submitted late (don't overwrite GRADED status)
                if ($submittedAt && Carbon::parse($submittedAt)->greaterThan($dueLimit) && $status !== 'GRADED') {
                    $status = 'LATE';
                    $lateCount++;
                }
            } else {
                if (Carbon::now()->greaterThan($dueLimit)) {
                    $status = 'MISSING';
                } else {
                    $status = 'NOT STARTED';
                }
            }

            $studentsGradingList[] = (object) [
                'siswa_id'     => $student->id,
                'name'         => $student->user->name ?? 'Siswa',
                'nisn'         => $student->nisn ?? '-',
                'status'       => $status,
                'file'         => $file,
                'link'         => $link,
                'catatan'      => $catatan,
                'jawaban_teks' => $jawaban_teks ?? null,
                'nilai'        => $nilaiVal,
                'submitted_at' => $submittedAt ? Carbon::parse($submittedAt)->translatedFormat('d M Y, H:i') : null
            ];
        }

        // Apply tab filtering
        $tab = $request->query('tab', 'all'); // all, ungraded, missing
        if ($tab === 'ungraded') {
            $studentsGradingList = array_filter($studentsGradingList, function($item) {
                return $item->status === 'SUBMITTED' || $item->status === 'LATE';
            });
        } elseif ($tab === 'missing') {
            $studentsGradingList = array_filter($studentsGradingList, function($item) {
                return $item->status === 'MISSING';
            });
        }

        // Calculate Stats
        $totalStudents = count($students);
        $totalTurnedIn = $turnedInCount + $gradedCount;
        $avgScore = $gradedCount > 0 ? round($totalScores / $gradedCount, 1) : 0;

        return view('guru.assignment.grade', compact(
            'tugas',
            'studentsGradingList',
            'totalStudents',
            'totalTurnedIn',
            'gradedCount',
            'lateCount',
            'avgScore',
            'tab'
        ));
    }

    public function gradeStore(Request $request, $id)
    {
        $guru = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($id);

        $request->validate([
            'grades' => 'nullable|array',
            'grades.*' => 'nullable|integer|min:0|max:1000',
            'feedbacks' => 'nullable|array',
            'feedbacks.*' => 'nullable|string|max:1000',
            'action' => 'required|string|in:save_draft,release',
        ]);

        $grades = $request->input('grades', []);
        $feedbacks = $request->input('feedbacks', []);
        $action = $request->action;

        foreach ($grades as $siswaId => $score) {
            if ($score === null) continue;

            $feedback = $feedbacks[$siswaId] ?? '';

            // Find or create submission record
            $submission = PengumpulanTugas::firstOrNew([
                'tugas_id' => $tugas->id,
                'siswa_id' => $siswaId,
            ]);

            $submission->nilai = $score;
            $submission->catatan = $feedback;

            if ($action === 'release') {
                $submission->status = 'dinilai';
            } else {
                // If it was already graded, keep it graded, otherwise keep as already submitted or draft status
                if ($submission->status !== 'dinilai') {
                    $submission->status = $submission->file_path || $submission->link ? 'sudah_mengumpulkan' : 'belum_mengumpulkan';
                }
            }

            $submission->save();

            // If released, let's sync average scores to global Nilai table for this student & subject!
            if ($action === 'release') {
                $this->syncStudentOverallGrade($siswaId, $tugas->matapelajaran_id, $tugas->kelas_id);
            }
        }

        $msg = $action === 'release' ? 'Nilai berhasil dirilis ke siswa.' : 'Draft nilai berhasil disimpan.';
        return redirect()->route('assignment.grade', $tugas->id)->with('success', $msg);
    }

    private function syncStudentOverallGrade($siswaId, $subjectId, $kelasId)
    {
        // Calculate average grade of all graded tasks for this subject and student
        $average = PengumpulanTugas::where('siswa_id', $siswaId)
            ->whereHas('tugas', function($q) use ($subjectId, $kelasId) {
                $q->where('matapelajaran_id', $subjectId)
                  ->where('kelas_id', $kelasId)
                  ->where('status', 'PUBLISHED');
            })
            ->where('status', 'dinilai')
            ->avg('nilai');

        if ($average !== null) {
            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'matapelajaran_id' => $subjectId,
                    'kelas_id' => $kelasId
                ],
                [
                    'nilai' => round($average)
                ]
            );
        }
    }

    public function gradeDetail($tugasId, $siswaId)
    {
        $guru  = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)
            ->with(['kelas', 'matapelajaran'])
            ->findOrFail($tugasId);

        $siswa = Siswa::with('user')->findOrFail($siswaId);

        $submission = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('siswa_id', $siswa->id)
            ->first();

        return view('guru.assignment.grade_detail', compact('tugas', 'siswa', 'submission'));
    }

    public function gradeDetailStore(Request $request, $tugasId, $siswaId)
    {
        $guru  = $this->getGuruOrAbort();
        $tugas = Tugas::where('guru_id', $guru->id)->findOrFail($tugasId);
        $siswa = Siswa::findOrFail($siswaId);

        $request->validate([
            'nilai'    => 'required|integer|min:0|max:' . $tugas->max_score,
            'feedback' => 'nullable|string|max:2000',
            'action'   => 'required|in:save_draft,release',
        ]);

        $submission = PengumpulanTugas::firstOrNew([
            'tugas_id' => $tugas->id,
            'siswa_id' => $siswa->id,
        ]);

        $submission->nilai        = $request->nilai;
        $submission->feedback_guru = $request->feedback;

        if ($request->action === 'release') {
            $submission->status = 'dinilai';
        } else {
            if ($submission->status !== 'dinilai') {
                $submission->status = ($submission->file_path || $submission->link || $submission->jawaban_teks)
                    ? 'sudah_mengumpulkan'
                    : 'belum_mengumpulkan';
            }
        }

        $submission->save();

        if ($request->action === 'release') {
            $this->syncStudentOverallGrade($siswa->id, $tugas->matapelajaran_id, $tugas->kelas_id);
        }

        $msg = $request->action === 'release'
            ? 'Nilai berhasil dirilis ke siswa.'
            : 'Draft nilai berhasil disimpan.';

        return redirect()
            ->route('assignment.grade', $tugas->id)
            ->with('success', $msg);
    }
}
