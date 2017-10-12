<div class="form-group required" id="form-description-error">
    {!! Form::label("description","Description *",["class"=>"control-label col-md-3"]) !!}
    <div class="col-md-6">
        {!! Form::text("description",null,["class"=>"form-control required"]) !!}
        <span id="description-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-value-error">
    {!! Form::label("value","Value *",["class"=>"control-label col-md-3"]) !!}
    <div class="col-md-6">
        {!! Form::text("value",null,["class"=>"form-control required"]) !!}
        <span id="value-error" class="help-block"></span>
    </div>
</div>
<div class="form-group required" id="form-date-error">
    {!! Form::label("date","Date",["class"=>"control-label col-md-3"]) !!}
    <div class="col-md-6">
        {!! Form::text("date", null,["placeholder"=>"0000-00-00 00:00:00","class"=>"form-control required"]) !!}
        <span id="date-error" class="help-block"></span>
    </div>
</div>

<div class="form-group">
    <div class="col-md-6 col-md-push-3">
        <a href="javascript:ajaxLoad('/list')" class="btn btn-danger"><i
                    class="glyphicon glyphicon-backward"></i>
            Back</a>
        {!! Form::button("<i class='glyphicon glyphicon-floppy-disk'></i> Save",["type" => "submit","class"=>"btn
    btn-primary"])!!}
    </div>
</div>
<script>
    $("#frm").submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var data = new FormData($(this)[0]);
        var url = form.attr("action");
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.fail) {
                    $('#frm input.required, #frm textarea.required').each(function () {
                        index = $(this).attr('id');
                        if (index in data.errors) {
                            $("#form-" + index + "-error").addClass("has-error");
                            $("#" + index + "-error").html(data.errors[index]);
                        }
                        else {
                            $("#form-" + index + "-error").removeClass("has-error");
                            $("#" + index + "-error").empty();
                        }
                    });
                    $('#focus').focus().select();
                } else {
                    $(".has-error").removeClass("has-error");
                    $(".help-block").empty();
                    ajaxLoad(data.url, data.content);
                }
            },
            error: function (xhr, textStatus, errorThrown) {
                alert(errorThrown);
            }
        });
        return false;
    });
</script>