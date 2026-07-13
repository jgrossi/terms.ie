@extends('errors.layout')

@section('title', 'Too many requests')
@section('code', 'Error 429')
@section('heading', 'Too many requests')
@section('message', 'You’ve made too many requests in a short time. Please wait a moment and try again.')
