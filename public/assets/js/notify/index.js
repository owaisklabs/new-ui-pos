'use strict';
var notify = $.notify('<i class="fa fa-bell-o"></i><strong>Loading</strong> Loading ...', {
    type: 'theme',
    allow_dismiss: true,
    delay: 1000,
    showProgressbar: true,
    timer: 500,
    animate:{
        enter:'animated fadeInDown',
        exit:'animated fadeOutUp'
    }
});

setTimeout(function() {
    notify.update('message', '<i class="fa fa-bell-o"></i><strong>Welome</strong> to Muktab MusslehUddin  .');
}, 300);
