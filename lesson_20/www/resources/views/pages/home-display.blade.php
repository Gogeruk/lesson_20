@extends('layout')

@section('home-display')
<div class="border border-warning m-12">
    <h5 class="text-center mb-3">PROJECT 1</h5>
    <h12 class="text-left m-1">Project auther: Boby</h12>
    <h12 class="text-left m-1">Creation date: 2020.12.12</h12>
    <table class="table table-sm table-warning">
        <thead>
            <tr>
                <th scope="col">Labels</th>
                <th scope="col">Users linked to this project</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Label 1</td>
                <td>User 1</td>
                <td class="text-center">
                    <a href="#" class="link link-danger border border-danger">DELETE</a>
                    <a href="#" class="link link-success border border-success">EDIT</a>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Label 2</td>
                <td>User 2</td>
                <td class="text-center">
                    <a href="#" class="link link-danger border border-danger">DELETE</a>
                    <a href="#" class="link link-success border border-success">EDIT</a>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Label 3</td>
                <td>User 3</td>
                <td class="text-center">
                    <a href="#" class="link link-danger border border-danger">DELETE</a>
                    <a href="#" class="link link-success border border-success">EDIT</a>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Label 4</td>
                <td></td>
                <td class="text-center">
                    <a href="#" class="link link-danger border border-danger">DELETE</a>
                    <a href="#" class="link link-success border border-success">EDIT</a>
                </td>
            </tr>
        </thead>
    </table>
    <button onclick="location.href=''" type="submit" class="btn btn-sm btn-warning m-1" name="button">DELETE PROJECT</button>
    <button onclick="location.href=''" type="submit" class="btn btn-sm btn-warning m-1" name="button">EDIT PROJECT</button>
    <button onclick="location.href=''" type="submit" class="btn btn-sm btn-warning m-1" name="button">LINK USERS</button>
</div>
@endsection
