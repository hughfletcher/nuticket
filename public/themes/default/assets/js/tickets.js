$(document).ready(function() {
    $(".users.select2").select2({
        ajax: {
            url:"{{ url('ajax/users') }}",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                // console.log(data);
                // return { results: data };
                //
                var results = $.map(data, function (v) {
                    return {"text":v[1],"id":v[0]}
                });
                console.log(reults)
                return { results: results };
                }
        }
    });
});