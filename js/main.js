$(document).ready(function(){
    var table = $('#data-table').DataTable({
        "order":[],
        "columndefs":[
            {
                "targets" : [4,5,6],
                "orderable":false
            },  
        ],
    })
})