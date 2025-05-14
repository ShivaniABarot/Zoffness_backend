/**
 * Custom DataTables initialization for Zoffness
 */

// Default DataTable configuration
const defaultDTConfig = {
    responsive: true,
    pageLength: 25,
    dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>' +
         '<"row"<"col-md-12"tr>>' +
         '<"row mt-3"<"col-md-5"i><"col-md-7"p>>',
    buttons: [
        {
            extend: 'excel',
            text: '<i class="bx bx-file me-1"></i> Excel',
            className: 'buttons-excel',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            }
        },
        {
            extend: 'pdf',
            text: '<i class="bx bx-file-pdf me-1"></i> PDF',
            className: 'buttons-pdf',
            exportOptions: {
                columns: ':visible:not(.no-export)'
            }
        }
    ],
    language: {
        search: "Search:",
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
        infoEmpty: "Showing 0 to 0 of 0 entries",
        infoFiltered: "(filtered from _MAX_ total entries)",
        zeroRecords: "No matching records found",
        emptyTable: "No data available in table",
        paginate: {
            first: '<i class="bx bx-chevrons-left"></i>',
            previous: '<i class="bx bx-chevron-left"></i>',
            next: '<i class="bx bx-chevron-right"></i>',
            last: '<i class="bx bx-chevrons-right"></i>'
        },
        processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
    }
};

// Initialize all DataTables with default configuration
document.addEventListener('DOMContentLoaded', function() {
    // Check if jQuery and DataTable are available
    if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
        console.error('jQuery or DataTable is not loaded');
        return;
    }

    // Apply default configuration to all tables with class 'datatable'
    $('.datatable').each(function() {
        const tableId = $(this).attr('id');
        
        // Skip if table is already initialized
        if ($.fn.DataTable.isDataTable('#' + tableId)) {
            return;
        }
        
        // Get custom options from data attributes if available
        let customOptions = {};
        try {
            customOptions = $(this).data('options') || {};
        } catch (e) {
            console.warn('Error parsing custom options for table #' + tableId, e);
        }
        
        // Merge default config with custom options
        const config = $.extend(true, {}, defaultDTConfig, customOptions);
        
        // Initialize DataTable
        $(this).DataTable(config);
    });
});

// Function to initialize a specific DataTable with custom options
function initDataTable(tableId, customOptions = {}) {
    if (typeof $ === 'undefined' || typeof $.fn.DataTable === 'undefined') {
        console.error('jQuery or DataTable is not loaded');
        return;
    }
    
    // Get the table element
    const $table = $('#' + tableId);
    
    // If table doesn't exist, log error and return
    if ($table.length === 0) {
        console.error('Table with ID ' + tableId + ' not found');
        return;
    }
    
    // If table is already initialized, destroy it first
    if ($.fn.DataTable.isDataTable('#' + tableId)) {
        $('#' + tableId).DataTable().destroy();
    }
    
    // Merge default config with custom options
    const config = $.extend(true, {}, defaultDTConfig, customOptions);
    
    // Initialize DataTable and return the instance
    return $table.DataTable(config);
}
