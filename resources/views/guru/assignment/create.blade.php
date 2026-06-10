@extends('layouts.main')

@section('main')
    <!-- Breadcrumbs & Header Actions -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box py-3">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0 font-12">
                        <li class="breadcrumb-item"><a href="{{ route('assignment.index') }}">Assignments</a></li>
                        <li class="breadcrumb-item active">Create New Assignment</li>
                    </ol>
                </div>
                <h2 class="text-dark fw-bold m-0 font-22">Create New Assignment</h2>
                <p class="text-muted font-13 mb-0">Define task parameters, scoring rubrics, and distribution targets.</p>
            </div>
        </div>
    </div>

    <!-- Main Creation Form -->
    <form action="{{ route('assignment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- Action Buttons Top Bar (Matches Mockup button bar style) -->
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="{{ route('assignment.index') }}" class="btn btn-danger font-13 px-4">Cancel</a>
                <button type="submit" name="status" value="DRAFT" class="btn btn-soft-blue font-13 px-4" style="background-color: rgba(91, 109, 240, 0.15); color: #5b6df0;">Save as Draft</button>
                <button type="submit" name="status" value="PUBLISHED" class="btn btn-success font-13 px-4">Publish Now</button>
            </div>
        </div>

        <div class="row">
            <!-- Left Columns: Basic Info & Attachments -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card shadow-sm border border-light mb-3">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <h4 class="header-title text-dark fw-bold m-0 font-14">
                            <i class="mdi mdi-information-outline me-1 text-blue"></i> Basic Information
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="mb-3">
                            <label class="form-label font-13 text-dark fw-semibold">Assignment Title</label>
                            <input type="text" name="title" class="form-control border @error('title') is-invalid @enderror" placeholder="e.g., Weekly Calculus Worksheet - Week 4" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label font-13 text-dark fw-semibold">Subject</label>
                                <select name="matapelajaran_id" class="form-select border @error('matapelajaran_id') is-invalid @enderror" required>
                                    <option value="">Select Subject</option>
                                    @foreach($subjects as $subj)
                                        <option value="{{ $subj->id }}" {{ old('matapelajaran_id') == $subj->id ? 'selected' : '' }}>{{ $subj->nama }}</option>
                                    @endforeach
                                </select>
                                @error('matapelajaran_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label font-13 text-dark fw-semibold">Assignment Type</label>
                                <select name="tipe" class="form-select border @error('tipe') is-invalid @enderror" required>
                                    <option value="Homework" {{ old('tipe') == 'Homework' ? 'selected' : '' }}>Homework</option>
                                    <option value="Quiz" {{ old('tipe') == 'Quiz' ? 'selected' : '' }}>Quiz</option>
                                    <option value="Practical" {{ old('tipe') == 'Practical' ? 'selected' : '' }}>Practical (Lab)</option>
                                    <option value="Writing" {{ old('tipe') == 'Writing' ? 'selected' : '' }}>Writing / Essay</option>
                                    <option value="Attendance" {{ old('tipe') == 'Attendance' ? 'selected' : '' }}>Attendance</option>
                                    <option value="Project" {{ old('tipe') == 'Project' ? 'selected' : '' }}>Project</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="form-label font-13 text-dark fw-semibold">Description / Instructions</label>
                            <textarea name="description" rows="6" class="form-control border @error('description') is-invalid @enderror" placeholder="Provide detailed instructions for the students. You can include links or formatting requirements." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Resources & Attachments Card -->
                <div class="card shadow-sm border border-light mb-3">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <h4 class="header-title text-dark fw-bold m-0 font-14">
                            <i class="mdi mdi-paperclip me-1 text-blue"></i> Resources & Attachments
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <!-- Custom File Upload Box -->
                        <div class="border rounded p-4 text-center cursor-pointer position-relative mb-3 bg-light bg-opacity-25" style="border: 2px dashed #d5dbdf !important; border-radius: 6px !important;">
                            <input type="file" name="file_tugas" id="file_tugas" class="position-absolute start-0 top-0 w-100 h-100 opacity-0 cursor-pointer" style="z-index: 10;">
                            <div class="avatar-sm bg-soft-blue rounded mx-auto mb-2 d-flex align-items-center justify-content-center" style="background-color: rgba(91, 109, 240, 0.12); width: 44px; height: 44px;">
                                <i class="fe-upload-cloud font-20 text-blue" style="color: #5b6df0 !important;"></i>
                            </div>
                            <h5 class="text-dark fw-bold m-0 font-13" id="upload-box-title">Click or Drag to Upload</h5>
                            <p class="text-muted font-11 mt-1 mb-2" id="upload-box-subtitle">PDF, DOCX, JPG or ZIP files (Max 25MB per file)</p>
                            <button type="button" class="btn btn-xs btn-light rounded border px-3">Browse Files</button>
                        </div>
                        @error('file_tugas')
                            <div class="text-danger font-12 mt-1 mb-2">{{ $message }}</div>
                        @enderror

                        <!-- Resource Link input -->
                        <div>
                            <label class="form-label font-13 text-dark fw-semibold">External Resource Link (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-muted border"><i class="fe-link"></i></span>
                                <input type="url" name="link" class="form-control border @error('link') is-invalid @enderror" placeholder="https://example.com/materials" value="{{ old('link') }}">
                            </div>
                            @error('link')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Columns: Scoring & Targets -->
            <div class="col-lg-4">
                <!-- Scoring & Grading Card -->
                <div class="card shadow-sm border border-light mb-3">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <h4 class="header-title text-dark fw-bold m-0 font-14">
                            <i class="mdi mdi-star-outline me-1 text-blue"></i> Scoring & Grading
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <div class="mb-3">
                            <label class="form-label font-13 text-dark fw-semibold">Maximum Score</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border"><i class="mdi mdi-star font-14 text-muted"></i></span>
                                <input type="number" name="max_score" class="form-control border" value="{{ old('max_score', 100) }}" required min="0" max="1000">
                            </div>
                            @error('max_score')
                                <div class="text-danger font-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="form-label font-13 text-dark fw-semibold">Passing Grade / KKM</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border"><i class="mdi mdi-check-circle-outline font-14 text-muted"></i></span>
                                <input type="number" name="kkm" class="form-control border" value="{{ old('kkm', 75) }}" required min="0" max="1000">
                            </div>
                            @error('kkm')
                                <div class="text-danger font-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Targets & Deadlines Card -->
                <div class="card shadow-sm border border-light mb-3">
                    <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                        <h4 class="header-title text-dark fw-bold m-0 font-14">
                            <i class="mdi mdi-clock-outline me-1 text-blue"></i> Targets & Deadlines
                        </h4>
                    </div>
                    <div class="card-body pt-2">
                        <!-- Target Classes checkboxes -->
                        <div class="mb-3">
                            <label class="form-label font-13 text-dark fw-semibold mb-1">Target Class(es)</label>
                            <div class="d-flex flex-column gap-2 bg-light bg-opacity-25 rounded p-2-5 border">
                                @foreach($classes as $cls)
                                    <div class="form-check">
                                        <input class="form-check-input border" type="checkbox" name="kelas_ids[]" value="{{ $cls->id }}" id="kelas_{{ $cls->id }}" {{ (is_array(old('kelas_ids')) && in_array($cls->id, old('kelas_ids'))) ? 'checked' : '' }}>
                                        <label class="form-check-label font-13 text-dark fw-medium" for="kelas_{{ $cls->id }}">
                                            Kelas {{ $cls->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('kelas_ids')
                                <div class="text-danger font-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label class="form-label font-13 text-dark fw-semibold">Due Date & Time</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border"><i class="mdi mdi-calendar-clock font-14 text-muted"></i></span>
                                <input type="datetime-local" name="due_date" class="form-control border" value="{{ old('due_date') }}" required>
                            </div>
                            @error('due_date')
                                <div class="text-danger font-12 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- JS script to display uploaded file name -->
    @section('js')
        <script>
            document.getElementById('file_tugas').addEventListener('change', function(e) {
                var fileName = e.target.files[0] ? e.target.files[0].name : "Click or Drag to Upload";
                document.getElementById('upload-box-title').innerText = fileName;
                document.getElementById('upload-box-subtitle').innerText = "File selected successfully. Submit to save.";
            });
        </script>
    @endsection
@endsection
