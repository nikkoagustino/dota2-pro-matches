<!DOCTYPE html>
<html>
<head>
    <title></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
<div id="result"></div>
<script>
    function ajaxRetrieve() {
        $.ajax({
            url: '{{ url('hero') }}',
            type: 'GET',
            dataType: 'html',
        })
        .always(function(result) {
            $('#result').html(result);
            console.log("complete");
        });
    }
    setInterval(ajaxRetrieve, 1500);
</script>   
</body>
</html>