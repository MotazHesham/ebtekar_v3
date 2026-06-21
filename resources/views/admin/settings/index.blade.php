@extends('layouts.admin')

@php
    $settingsGroupMeta = [
        'general' => ['icon' => 'fa-cogs', 'color' => '#6366f1'],
        'social_media' => ['icon' => 'fa-share-alt', 'color' => '#f472b6'],
        'seo' => ['icon' => 'fa-search', 'color' => '#34d399'],
        'features' => ['icon' => 'fa-toggle-on', 'color' => '#f59e0b'],
    ];
    $settingsFallbackColors = ['#38bdf8', '#a78bfa', '#fb923c', '#fbbf24', '#2dd4bf', '#60a5fa'];
@endphp

@section('styles')
    <style>
        .settings-nav-panel {
            background: linear-gradient(160deg, #f8fafc 0%, #eef2ff 55%, #fdf4ff 100%);
            border: 1px solid rgba(99, 102, 241, 0.12);
            border-radius: 1rem;
            padding: 0.75rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
        }

        .settings-nav-panel .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            margin-bottom: 0.5rem;
            padding: 0.85rem 1rem;
            border: none;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.72);
            color: #334155;
            font-weight: 600;
            text-align: start;
            transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease, color 0.25s ease;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        }

        .settings-nav-panel .nav-link:last-child {
            margin-bottom: 0;
        }

        .settings-nav-panel .nav-link:hover {
            transform: translateX(4px);
            background: #fff;
            color: var(--settings-item-color, #6366f1);
            box-shadow: 0 6px 18px color-mix(in srgb, var(--settings-item-color, #6366f1) 18%, transparent);
        }

        [dir="rtl"] .settings-nav-panel .nav-link:hover {
            transform: translateX(-4px);
        }

        .settings-nav-panel .nav-link.active {
            background: linear-gradient(
                135deg,
                color-mix(in srgb, var(--settings-item-color, #6366f1) 88%, #fff) 0%,
                var(--settings-item-color, #6366f1) 100%
            );
            color: #fff;
            box-shadow: 0 8px 22px color-mix(in srgb, var(--settings-item-color, #6366f1) 42%, transparent);
        }

        .settings-nav-panel .nav-link.active .settings-nav-icon {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.25);
        }

        .settings-nav-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 0.65rem;
            flex-shrink: 0;
            background: color-mix(in srgb, var(--settings-item-color, #6366f1) 14%, #fff);
            color: var(--settings-item-color, #6366f1);
            font-size: 0.95rem;
            transition: background 0.25s ease, color 0.25s ease;
        }

        .settings-nav-label {
            line-height: 1.3;
        }
    </style>
@endsection

@section('content')
    <div class="card custom-card">
        <div class="card-header">
            <div class="card-title">
                {{ trans('cruds.setting.title') }}
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="nav flex-column nav-pills settings-nav-panel" id="v-pills-tab" role="tablist"
                        aria-orientation="vertical">
                        @foreach ($settings as $group_name => $raws)
                            @php
                                $meta = $settingsGroupMeta[$group_name] ?? [
                                    'icon' => 'fa-sliders-h',
                                    'color' => $settingsFallbackColors[$loop->index % count($settingsFallbackColors)],
                                ];
                            @endphp
                            <button
                                class="nav-link @if ($loop->first) active @endif"
                                id="v-pills-{{ $group_name }}-tab" data-toggle="pill"
                                data-target="#v-pills-{{ $group_name }}" type="button" role="tab"
                                aria-controls="v-pills-{{ $group_name }}"
                                aria-selected="@if ($loop->first) true @else false @endif"
                                style="--settings-item-color: {{ $meta['color'] }};">
                                <span class="settings-nav-icon">
                                    <i class="fas {{ $meta['icon'] }}" aria-hidden="true"></i>
                                </span>
                                <span class="settings-nav-label">{{ trans('cruds.setting.group_name.' . $group_name) }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-9">
                    @include('partials.switchlang')
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="lang" value="{{ currentEditingLang() }}" id="">

                        <div class="tab-content" id="v-pills-tabContent">
                            @foreach ($settings as $group_name => $raws)
                                <div class="tab-pane fade @if ($loop->first) show active @endif text-muted"
                                    id="v-pills-{{ $group_name }}" role="tabpanel"
                                    aria-labelledby="v-pills-{{ $group_name }}">
                                    <div class="row">
                                        @foreach ($raws as $raw)
                                            @include('admin.settings.inputs', ['setting' => $raw])
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">
                            {{ trans('global.save') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            function SimpleUploadAdapter(editor) {
                editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
                    return {
                        upload: function() {
                            return loader.file
                                .then(function(file) {
                                    return new Promise(function(resolve, reject) {
                                        // Init request
                                        var xhr = new XMLHttpRequest();
                                        xhr.open('POST',
                                            '{{ route('admin.settings.storeCKEditorImages') }}',
                                            true);
                                        xhr.setRequestHeader('x-csrf-token', window._token);
                                        xhr.setRequestHeader('Accept', 'application/json');
                                        xhr.responseType = 'json';

                                        // Init listeners
                                        var genericErrorText =
                                            `Couldn't upload file: ${ file.name }.`;
                                        xhr.addEventListener('error', function() {
                                            reject(genericErrorText)
                                        });
                                        xhr.addEventListener('abort', function() {
                                            reject()
                                        });
                                        xhr.addEventListener('load', function() {
                                            var response = xhr.response;

                                            if (!response || xhr.status !== 201) {
                                                return reject(response && response
                                                    .message ?
                                                    `${genericErrorText}\n${xhr.status} ${response.message}` :
                                                    `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`
                                                );
                                            }

                                            $('form').append(
                                                '<input type="hidden" name="ck-media[]" value="' +
                                                response.id + '">');

                                            resolve({
                                                default: response.url
                                            });
                                        });

                                        if (xhr.upload) {
                                            xhr.upload.addEventListener('progress', function(
                                                e) {
                                                if (e.lengthComputable) {
                                                    loader.uploadTotal = e.total;
                                                    loader.uploaded = e.loaded;
                                                }
                                            });
                                        }

                                        // Send request
                                        var data = new FormData();
                                        data.append('upload', file);
                                        data.append('crud_id', '0');
                                        xhr.send(data);
                                    });
                                })
                        }
                    };
                }
            }

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                    allEditors[i], {
                        extraPlugins: [SimpleUploadAdapter]
                    }
                );
            }
        });
    </script>
@endsection
