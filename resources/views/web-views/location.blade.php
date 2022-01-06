<script src="{{asset('public/assets/front-end')}}/vendor/jquery/dist/jquery-2.2.4.min.js"></script>
<script>
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successFunction, errorFunction);
    }

    function successFunction(position) {

        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        // Apply setCookie
        /* setCookie('lat', position.coords.latitude, 30);
        setCookie('lng', position.coords.longitude, 30); */
        $.ajax({
            url: 'location',
            type: 'POST',
            data: { _token: "{{ csrf_token() }}", lng: lng, lat: lat },
            dataType: "JSON",
            success: function(result) {
                if (result.status == 'OK') {
                    window.location.href = "{{ URL::to('/') }}";
                }else{
                    location.reload();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                
            }
        });
    }

    function errorFunction() {
        alert("Geocoder failed");
    }

        // Set a Cookie
    function setCookie(cName, cValue, expDays) {
            let date = new Date();
            date.setTime(date.getTime() + (expDays * 24 * 60 * 60 * 1000));
            const expires = "expires=" + date.toUTCString();
            document.cookie = cName + "=" + cValue + "; " + expires + "; path=/";
    }
    
</script>