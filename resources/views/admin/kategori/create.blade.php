@extends('admin.layout')
@section('page-title','Tambah Kategori')
@section('content')<div class="form-card"><form method="POST" action="{{ route('admin.kategori.store') }}">@csrf @include('admin.kategori.form',['submitLabel'=>'Simpan Kategori'])</form></div>@endsection
