//This is the script that controls the single-page app (page loading)
$(document).ready(function() {
  hash_handle(); //load main page
  //main listeners:
  $(window).bind('hashchange', hash_handle );//use hash_handle function when hash changes
  $('#sel_lang').change(lang_change);//listener on language selector
  lang_setup(); //setup language
  get_top_articles();
});

function setListeners(){
  //when page content changes, we need to run these again
  $( 'a[type!="local"]' ).attr("target", "_blank");

}

function hash_handle(){
  var h = new RegExp("^#!/");
  var page = window.location.hash.replace(h,"");
  if (page==""){
    setpage("main");
  }else{
    setpage(page);
  }
}

function setpage(destination){
  $('#loading_main').show();
  $('#inner_page').hide();
  $.ajax({
    url: "wkipe-dir/php/getpage.php", 
    data: {page: destination, lang: $.cookie('lang')},
    success: function(data, textStatus, jqXHR){
      $('#inner_page').html(data);
    },
    async: false
  });
  $('#loading_main').hide();
  $('#inner_page').show();
  setListeners();
}

function get_top_articles(){
  $.ajax({
    url: "wkipe-dir/php/toplinks.php", 
    success: function(data, textStatus, jqXHR){
      var datajson = $.parseJSON(data);
      var normalarticles = "<li><em>Top Normal Articles</em></li>";
      var customarticles = "<li><em>Top Custom Articles</em></li>";
      var recentarticles = "<li><em>Recently Accessed</em></li>";
      var d = datajson[0];
      $('#span_articlecount').html('<h3>'+d+' redirects and counting!</h3>');
      d = datajson[1];
      var host = new RegExp("[a-z0-9]?\.?wki\.pe/");
      for (var i=0;i<d.length;i++){
        normalarticles=normalarticles+'<li><a href="http://'+d[i]+'">'+d[i].replace(host,"")+'</a></li>';
      }
      d = datajson[2];
      for (var j=0;j<d.length;j++){
        customarticles=customarticles+'<li><a href="http://'+d[j]+'">'+d[j].replace(host,"")+'</a></li>';
      }
      d = datajson[3];
      for (var k=0;k<d.length;k++){
        recentarticles=recentarticles+'<li><a href="http://'+d[k]+'">'+d[k].replace(host,"")+'</a></li>';
      }
      $('#ul_topnormal').html(normalarticles);
      $('#ul_topcustom').html(customarticles);
      $('#ul_recent').html(recentarticles);
    }
  });
}

function lang_setup(){
  var mylang = $.cookie('lang');
  //alert(mylang);
  if (mylang==null){
    $.ajax({
      url: "wkipe-dir/php/lang.php",
      success: function(data, textStatus, jqXHR){
        mylang=data;
        $.cookie('lang',mylang);
      },
      error: function(){//if it doesn't work, default to English
        mylang="en";
        $.cookie('lang',"en");
      }
    });
  }
  //alert(mylang);
  var langs = ["en", "nl", "de", "sv", "fr", "it", "es", "ru", "pl", "ja", "vi", "pt", "war", "ceb", "zh", "uk", "ca"];
  var selected = "";
  for(var i=0; i<langs.length;i++){
    if (langs[i]==mylang){
      selected=" selected";
    }else{
      selected="";
    }
    $('#sel_lang').append("<option value='"+langs[i]+"'"+selected+">"+langs[i]+"</option>");
  }
}

function lang_change(){
  $.cookie('lang',$('#sel_lang').val());
}