@extends('admin.layout')
@section('page-title','Edit Kategori')
@section('content')<div class="form-card"><form method="POST" action="{{ route('admin.kategori.update',$kategori) }}">@csrf @method('PUT') @include('admin.kategori.form',['submitLabel'=>'Simpan Perubahan'])</form></div>@endsection
