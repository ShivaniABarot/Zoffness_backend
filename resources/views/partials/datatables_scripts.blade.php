{{-- DataTables CSS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

{{-- DataTables JS --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

{{-- Additional styling for DataTables --}}
<style>
    .dataTables_wrapper .dataTables_length, 
    .dataTables_wrapper .dataTables_filter, 
    .dataTables_wrapper .dataTables_info, 
    .dataTables_wrapper .dataTables_processing, 
    .dataTables_wrapper .dataTables_paginate {
        margin-bottom: 10px;
        color: #333;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.3em 0.8em;
        border: 1px solid #ddd;
        margin-left: 2px;
        cursor: pointer;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #696cff;
        color: white !important;
        border: 1px solid #696cff;
    }
    
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
        background: #f0f0f0;
        color: #333 !important;
    }
    
    table.dataTable thead th, table.dataTable thead td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    
    table.dataTable tbody td {
        padding: 8px 10px;
    }
    
    table.dataTable.stripe tbody tr.odd, 
    table.dataTable.display tbody tr.odd {
        background-color: #f9f9f9;
    }
    
    table.dataTable.hover tbody tr:hover, 
    table.dataTable.display tbody tr:hover {
        background-color: #f3f3f3;
    }
</style>
