@extends('layouts.app')

@section('content')
<h1>My Account</h1>
<h2>Current Balance {{ auth()->user()->merits->balance() }} BM</h2>
    <p>Your current merit balance is calculated by your total Merit points minus any deductions for failing to come
        equipped to lessons and minus previous orders.  50 merits are deducted if you fail
        to have suitable equipment for a lesson eg Pen, Pencil, ruler or PE Kit.</p>
    <hr>
    <h3>Merit Calculation</h3>
    <table class="table table-striped">
        <tr>
            <th>Raw Merit Points:</th>
            <td id="raw-merit-points" class="text-right"><img src="/images/loading_circle.gif" alt="Loading"></td>
        </tr>
        <tr>
            <th>Equipment Deductions:</th>
            <td id="equipment-deductions" class="text-right"><img src="/images/loading_circle.gif" alt="Loading"></td>
        </tr>
        <tr>
            <th>Total Orders:</th>
            <td class="text-right">{{ ($orderSum * -1) }} BM</td>
        </tr>
        <tr>
            <th>Balance</th>
            <td class="text-right">{{ auth()->user()->merits->balance() }} BM</td>
        </tr>
    </table>


@endsection

@section('scripts')
<script type="text/javascript">
    $(function() {
        getBehaviourData();
    });

    function getBehaviourData() {
        $("#merit-balance-status span").html('<img src="/images/loading_circle2.gif" alt="Loading">');
        $.ajax({
            url: "https://vle.burnsidecollege.org.uk/behaviour/get-behaviour-data/letmein/{{ auth()->user()->username }}",
            type: "get",
            dataType: "jsonp",
            jsonpCallback: "behaviourDataCallback",
            complete: function() {

            }
        });
    }

    function behaviourDataCallback(data) {
        // fire of check to see if data has been written to xml dir
        if (!data.file_exists) {
            setTimeout(getBehaviourData, 500);
        } else {
            var totalPoints = 0;
            $.each(data.behaviour, function(i, b) {
                totalPoints += parseInt(b.EventPoints);
            });

            $("#raw-merit-points").html(totalPoints+" BM");

            var equipmentDeductions = data.equipment * 10;

            if(equipmentDeductions > 0) {
                equipmentDeductions = "-"+equipmentDeductions;
            }

            $("#equipment-deductions").html(equipmentDeductions+" BM");

            totalPoints = totalPoints - (data.equipment * 10);

            //$("#merit-balance-status span").html(totalPoints+" BM");

            var postData = {};
            postData.points = totalPoints;

            $.ajax({
                url: '/merits',
                dataType: "json",
                type: "POST",
                data: postData,
                success: function(data) {
                    $("#merit-balance-status span").html(data.balance+" BM");
                }
            })
        }
    }
</script>
@endsection