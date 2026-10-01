@extends('backend.app')

@section('title', 'Edit User')
@section('title_url')
    <a href="{{ route('user.index') }}">Users</a>
@endsection
@section('tabName')
    Home
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.0/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="{{ asset('vendor/flasher/flasher.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.css">
    <style>
        .premium-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
            border: 1px solid #f1f5f9;
        }

        .form-input {
            border: 2px solid #f0f3f7;
            border-radius: 6px;
        }

        .form-section {
            display: grid;
            gap: 1.25rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.25rem;
        }

        .form-row-full {
            display: grid;
            gap: 1.25rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #334155;
        }

        .form-control {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 0.75rem;
            padding: 0.9rem 1rem;
            font-size: 0.95rem;
            color: #0f172a;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            background: #f8fafc;
            color: #334155;
            padding: 0.85rem 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
        }

        .children-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem;
        }

        .child-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1rem;
            position: relative;
        }

        .child-item+.child-item {
            margin-top: 1rem;
        }

        .remove-child {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            color: #ef4444;
            border-radius: 9999px;
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .remove-child:hover {
            background: #fee2e2;
        }

        .dropify-wrapper {
            border-radius: 16px;
            border: 2px dashed #e2e8f0;
        }
    </style>
@endpush

@section('content')
    <div class="premium-card overflow-hidden mb-8">
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800">Edit User Profile</h2>
                    <p class="text-slate-500 text-sm mt-1">Update user account and parent profile details.</p>
                </div>
                <a href="{{ route('user.show', $data->id) }}" class="btn-secondary">Back to Profile</a>
            </div>

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-green-700 mb-6">{{ session('success') }}
                </div>
            @endif
            @if (session('t-error'))
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-red-700 mb-6">{{ session('t-error') }}</div>
            @endif

            <form action="{{ route('user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <div class="form-row">
                        <div>
                            <label class="form-label" for="name">Full Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $data->name) }}"
                                class="form-control" required>
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="email">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $data->email) }}"
                                class="form-control" required>
                            @error('email')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label class="form-label" for="role">Role</label>
                            <input id="role" name="role" type="text" value="{{ old('role', $data->role) }}"
                                class="form-control" required>
                            @error('role')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="status">Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active" {{ old('status', $data->status) === 'active' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="inactive"
                                    {{ old('status', $data->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label class="form-label" for="birth_date">Parent Birth Date</label>
                            <input id="birth_date" name="birth_date" type="date"
                                value="{{ old('birth_date', optional($data->profile)->birth_date ? optional($data->profile)->birth_date->format('Y-m-d') : '') }}"
                                class="form-control" required>
                            @error('birth_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="parent_role">Parent Role</label>
                            <select id="parent_role" name="parent_role" class="form-control">
                                <option value="father"
                                    {{ old('parent_role', optional($data->profile)->parent_role) === 'father' ? 'selected' : '' }}>
                                    Father</option>
                                <option value="mother"
                                    {{ old('parent_role', optional($data->profile)->parent_role) === 'mother' ? 'selected' : '' }}>
                                    Mother</option>
                            </select>
                            @error('parent_role')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div>
                            <label class="form-label" for="country">Country</label>
                            <input id="country" name="country" type="text"
                                value="{{ old('country', optional($data->profile)->country) }}" class="form-control" required>
                            @error('country')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="form-label" for="avatar">Avatar</label>
                            <input id="avatar" name="avatar" type="file" accept="image/*" class="dropify"
                                data-default-file="{{ $data->avatar ? asset($data->avatar) : asset('backend/user.png') }}" />
                            @error('avatar')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="children-card">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-700">Children</h3>
                                <p class="text-sm text-slate-500">Add or update the user's children information.</p>
                            </div>
                            <button type="button" id="addChildBtn" class="btn-secondary">Add Child</button>
                        </div>

                        <div id="childrenWrapper">
                            @foreach (old(
            'children',
            $data->children->map(function ($child) {
                    return ['id' => $child->id, 'name' => $child->name, 'birth_date' => optional($child->birth_date)->format('Y-m-d')];
                })->toArray(),
        ) as $index => $child)
                                <div class="child-item">
                                    <button type="button" class="remove-child" data-index="{{ $index }}"
                                        aria-label="Remove child">×</button>
                                    <input type="hidden" name="children[{{ $index }}][id]"
                                        value="{{ $child['id'] ?? '' }}">
                                    <div class="form-row">
                                        <div>
                                            <label class="form-label">Child Name</label>
                                            <input name="children[{{ $index }}][name]" type="text"
                                                value="{{ old('children.' . $index . '.name', $child['name'] ?? '') }}"
                                                class="form-control" placeholder="Child name">
                                        </div>
                                        <div>
                                            <label class="form-label">Birth Date</label>
                                            <input name="children[{{ $index }}][birth_date]" type="date"
                                                value="{{ old('children.' . $index . '.birth_date', $child['birth_date'] ?? '') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="childrenDeleted"></div>
                    </div>

                    <div class="flex justify-end gap-3 mt-4">
                        <a href="{{ route('user.show', $data->id) }}" class="btn-secondary">Cancel</a>
                        <button type="submit" class="btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <script>
        function escapeHtml(text) {
            return text === undefined || text === null ? '' : String(text).replace(/[&<>'"]/g, function(char) {
                return {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    "'": '&#39;',
                    '"': '&quot;'
                } [char];
            });
        }

        function createChildItem(index, child = {}) {
            return `
                <div class="child-item">
                    <button type="button" class="remove-child" aria-label="Remove child">×</button>
                    <input type="hidden" name="children[${index}][id]" value="${escapeHtml(child.id || '')}">
                    <div class="form-row">
                        <div>
                            <label class="form-label">Child Name</label>
                            <input name="children[${index}][name]" type="text" value="${escapeHtml(child.name || '')}" class="form-control" placeholder="Child name">
                        </div>
                        <div>
                            <label class="form-label">Birth Date</label>
                            <input name="children[${index}][birth_date]" type="date" value="${escapeHtml(child.birth_date || '')}" class="form-control">
                        </div>
                    </div>
                </div>
            `;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const childrenWrapper = document.getElementById('childrenWrapper');
            const childrenDeleted = document.getElementById('childrenDeleted');
            const addChildBtn = document.getElementById('addChildBtn');
            let childIndex = childrenWrapper.querySelectorAll('.child-item').length;

            $('.dropify').dropify();

            addChildBtn.addEventListener('click', function() {
                const html = createChildItem(childIndex);
                childrenWrapper.insertAdjacentHTML('beforeend', html);
                childIndex += 1;
            });

            childrenWrapper.addEventListener('click', function(event) {
                if (!event.target.closest('.remove-child')) {
                    return;
                }

                const item = event.target.closest('.child-item');
                const childIdInput = item.querySelector('input[name$="[id]"]');
                const childId = childIdInput ? childIdInput.value : null;

                if (childId) {
                    const deleteInput = document.createElement('input');
                    deleteInput.type = 'hidden';
                    deleteInput.name = 'children_to_delete[]';
                    deleteInput.value = childId;
                    childrenDeleted.appendChild(deleteInput);
                }

                item.remove();
            });
        });
    </script>
@endpush
