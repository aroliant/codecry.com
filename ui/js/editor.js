function loaderShow(){
  $("#loader").show(500);
}
function loaderHide(){
  $("#loader").hide(500);
}

loaderShow();
var editor;
// Tell RequireJS where ace is located
// require.config({
//     paths: {
//         'ace': 'ace/lib/ace'
//     }
// });

// Load the ace modules
// require(['ace/ace'], function(ace) {
    // Set up the editor
    editor = ace.edit('editor');
    editor.setTheme('ace/theme/monokai');
    editor.getSession().setMode('ace/mode/c_cpp');
    loaderHide();
// });

$("form input").click(function(){
  if($("form input").val()=="python2"){
    editor.getSession().setMode('ace/mode/python');
  }
  else if($("form input").val()=="php"){
    editor.getSession().setMode('ace/mode/php');
  }
  else if($("form input").val()=="java"){
    editor.getSession().setMode('ace/mode/java');
  }else{
    editor.getSession().setMode('ace/mode/c_cpp');
  }
});


var opts = {
            lines: 10, // The number of lines to draw
            length: 7, // The length of each line
            width: 4, // The line thickness
            radius: 10, // The radius of the inner circle
            corners: 1, // Corner roundness (0..1)
            rotate: 0, // The rotation offset
            color: '#FFF', // #rgb or #rrggbb
            speed: 1, // Rounds per second
            trail: 60, // Afterglow percentage
            shadow: false, // Whether to render a shadow
            hwaccel: false, // Whether to use hardware acceleration
            className: 'spinner', // The CSS class to assign to the spinner
            zIndex: 2e9, // The z-index (defaults to 2000000000)
            top: 25, // Top position relative to parent in px
            left: 25 // Left position relative to parent in px
        };
        var target = document.getElementById('loader');
        var spinner = new Spinner(opts).spin(target);


function compile () {
loaderShow();
//var data = editor.getValue();
var data = btoa(editor.getValue());
console.log(data);
$.ajax({
  url: 'http://mercury.deploy.aroliant.com/compile/' + $('input[name=lang]:checked', 'form').val(),
  type: 'GET',
  data: {
    code: data
  },
  success: function(msg) {
    $('.displayHolder').show();
    $('#display').html(msg);
    loaderHide();
},
error: function(msg) {
  $('#display').html("Unable to connect Internet !");
  loaderHide();
}
});
}


$("#compile").click(function(){
  compile();
});


function base64_encode(data) {
  //  discuss at: http://phpjs.org/functions/base64_encode/
  // original by: Tyler Akins (http://rumkin.com)
  // improved by: Bayron Guevara
  // improved by: Thunder.m
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // improved by: Rafał Kukawski (http://kukawski.pl)
  // bugfixed by: Pellentesque Malesuada
  //   example 1: base64_encode('Kevin van Zonneveld');
  //   returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
  //   example 2: base64_encode('a');
  //   returns 2: 'YQ=='
  //   example 3: base64_encode('✓ à la mode');
  //   returns 3: '4pyTIMOgIGxhIG1vZGU='

  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = [];

  if (!data) {
    return data;
  }

  data = unescape(encodeURIComponent(data));

  do {
    // pack three octets into four hexets
    o1 = data.charCodeAt(i++);
    o2 = data.charCodeAt(i++);
    o3 = data.charCodeAt(i++);

    bits = o1 << 16 | o2 << 8 | o3;

    h1 = bits >> 18 & 0x3f;
    h2 = bits >> 12 & 0x3f;
    h3 = bits >> 6 & 0x3f;
    h4 = bits & 0x3f;

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
  } while (i < data.length);

  enc = tmp_arr.join('');

  var r = data.length % 3;

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
}
