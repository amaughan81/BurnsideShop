@if($syncMerits > 0)
<script type="text/javascript">
    $(function() {

        getBehaviourData();

    });

    function getBehaviourData() {
        $("#merit-balance-status span").html('<img src="/images/loading_circle2.gif" alt="Loading">');
        $.ajax({
            url: "https://vle.burnsidecollege.org.uk/behaviour/get-behaviour-data/letmein/{{ $syncMerits }}",
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

            totalPoints = totalPoints - (data.equipment * 10);

            //$("#merit-balance-status span").html(totalPoints+" BM");

            var postData = {};
            postData.points = totalPoints;
            if($("#select-student").length > 0) {
                postData.user_id = $("#select-student").val();
            }

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
@endif