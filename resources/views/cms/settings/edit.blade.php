@extends('cms.layouts.app')
@section('title', 'Pengaturan & SEO')
@section('content')
@php($titles = ['general' => 'Identitas Situs', 'seo' => 'SEO & Tampilan Saat Dibagikan', 'analytics' => 'Analytics & Ads'])
<form method="post" action="{{ route('cms.settings.update') }}" enctype="multipart/form-data">@csrf @method('put')
@foreach($groups as $group => $items)
<section class="settings-group"><h2>{{ $titles[$group] ?? ucfirst($group) }}</h2><div class="card"><div class="card-body"><div class="field-grid">
@foreach($items as $setting)
<div class="field {{ in_array($setting->type, ['textarea','image']) ? 'field-full' : '' }}">
<label for="{{ $setting->key }}">{{ $setting->label }}</label>
@if($setting->type === 'textarea')<textarea id="{{ $setting->key }}" name="{{ $setting->key }}">{{ old($setting->key, $setting->value) }}</textarea>
@elseif($setting->type === 'image')
@if($setting->value)<img class="preview-logo" src="{{ asset($setting->value) }}" alt="Preview {{ $setting->label }}">@endif
<input id="{{ $setting->key }}" name="{{ $setting->key }}" type="file" accept=".webp,.jpg,.jpeg,.png">
@else<input id="{{ $setting->key }}" name="{{ $setting->key }}" type="{{ $setting->type === 'url' ? 'url' : 'text' }}" value="{{ old($setting->key, $setting->value) }}">@endif
@if($setting->help_text)<small>{{ $setting->help_text }}</small>@endif
</div>
@endforeach
</div></div></div></section>
@endforeach
<div class="form-footer"><button class="btn btn-primary" type="submit">Simpan Semua Pengaturan</button></div></form>
@endsection
