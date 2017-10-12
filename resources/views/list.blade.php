<h1 class="page-header">Records
    <div class="pull-right">
        <a href="javascript:ajaxLoad('/create')" class="btn btn-success pull-right"><i
                    class="glyphicon glyphicon-plus-sign"></i> New</a>
    </div>
</h1>
<div class="col-sm-3 form-group">
    <div class="input-group">
        <input class="form-control" id="date_from" value="{{ Session::get('date_from') }}"
               type="date">
        <input class="form-control" id="date_to" value="{{ Session::get('date_to') }}"
               type="date">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default"
                    onclick="ajaxLoad('{{url('/list')}}?ok=1&date_from='+$('#date_from').val()+'&date_to='+$('#date_to').val())">
                <i class="glyphicon glyphicon-refresh"></i>
            </button>
        </div>
    </div>
</div>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>
            <a href="javascript:ajaxLoad('/list?field=description&sort={{Session::get("record_sort")=="asc"?"desc":"asc"}}')">
                Description
            </a>
        </th>
        <th>
            <a href="javascript:ajaxLoad('/list?field=value&sort={{Session::get("record_sort")=="asc"?"desc":"asc"}}')">
                UAH
            </a>
        </th>
        <th>
            <a href="javascript:ajaxLoad('/list?field=value_usd&sort={{Session::get("record_sort")=="asc"?"desc":"asc"}}')">
                USD
            </a>
        </th>
        <th>
            <a href="javascript:ajaxLoad('/list?field=date&sort={{Session::get("record_sort")=="asc"?"desc":"asc"}}')">
                Date
            </a>
        </th>
        <th width="140px"></th>
    </tr>
    </thead>
    <tbody>
    <?php $pos['uah'] = 0; $pos['usd'] = 0; $neg['uah'] = 0; $neg['usd'] = 0;?>
    @foreach($records as $key=>$record)
        <?php
        // UAH
        if ($record->value > 0)
            $pos['uah'] += $record->value;
        else
            $neg['uah'] += $record->value;
        // USD
        if ($record->value_usd > 0)
            $pos['usd'] += $record->value_usd;
        else
            $neg['usd'] += $record->value_usd;
        ?>
        <tr>
            <td>{{$record->description}}</td>
            <td>{{$record->value}}</td>
            <td>{{$record->value_usd}}</td>
            <td>{{$record->date}}</td>
            <td style="text-align: center">
                <a class="btn btn-primary btn-xs" title="Edit"
                   href="javascript:ajaxLoad('/update/{{$record->id}}')">
                    <i class="glyphicon glyphicon-edit"></i> Edit</a>
                <a class="btn btn-danger btn-xs" title="Delete"
                   href="javascript:if(confirm('Are you sure want to delete?')) ajaxLoad('/delete/{{$record->id}}')">
                    <i class="glyphicon glyphicon-trash"></i> Delete
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="pull-left">
    <p>Summary: </p>
    <p><span class="summ">{{ $pos['uah']+$neg['uah'] }}</span> UAH
        (<span style="color: green">+{{ $pos['uah'] }}</span> | <span style="color: red">-{{ abs($neg['uah']) }}</span>)
    </p>
    <p><span class="summ">{{ $pos['usd']+$neg['usd'] }}</span> USD
        (<span style="color: green">+{{ $pos['usd'] }}</span> | <span style="color: red">-{{ abs($neg['usd']) }}</span>)
    </p>
</div>

<div class="pull-left">{!! str_replace('/?','?',$records->render()) !!}</div>

<script>
    $('.pagination a').on('click', function (event) {
        event.preventDefault();
        ajaxLoad($(this).attr('href'));
    });
</script>
<script>
    $(document).ready(function () {
        if (parseInt($('.summ').text()) >= 0)
            $('.summ').css('color', 'green');
        else
            $('.summ').css('color', 'red');
    });
</script>