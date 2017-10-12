@extends('template')

@section('content')
    <div class="container-fluid">
        <div class="row"></div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <div id="content"></div>
            </div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <script>
        function ajaxLoad(filename, content) {
            content = typeof content !== 'undefined' ? content : 'content';
            $.ajax({
                type: "GET",
                url: filename,
                contentType: false,
                success: function (data) {
                    $("#" + content).html(data);
                },
                error: function (xhr, status, error) {
                    alert(xhr.responseText);
                }
            });
        }

        $(document).ready(function () {
            ajaxLoad('/list');
        });
    </script>
@stop