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
    data: {page: destination, lang: Cookies.get('lang')},
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
      var normalarticles = "<li><em>Top Normal Articles</em></li>";
      var customarticles = "<li><em>Top Custom Articles</em></li>";
      var recentarticles = "<li><em>Recently Accessed</em></li>";
      $('#span_articlecount').html('<h3>'+data.total_redirects+' redirects and counting!</h3>');
      var host = new RegExp("(https?:\/\/)?[a-z0-9]?\.?wki\.pe/");
      for (var i=0;i<data.top_normal.length;i++){
        normalarticles=normalarticles+'<li><a href="'+data.top_normal[i]+'">'+data.top_normal[i].replace(host,"")+'</a></li>';
      }
      for (var j=0;j<data.top_custom.length;j++){
        customarticles=customarticles+'<li><a href="'+data.top_custom[j]+'">'+data.top_custom[j].replace(host,"")+'</a></li>';
      }
      for (var k=0;k<data.recent.length;k++){
        recentarticles=recentarticles+'<li><a href="'+data.recent[k]+'">'+data.recent[k].replace(host,"")+'</a></li>';
      }
      $('#ul_topnormal').html(normalarticles);
      $('#ul_topcustom').html(customarticles);
      $('#ul_recent').html(recentarticles);
    }
  });
}

function lang_setup(){
  var mylang = Cookies.get('lang');
  console.log("cookie lang", mylang);
  //alert(mylang);
  if (mylang==null){
    $.ajax({
      url: "wkipe-dir/php/lang.php",
      success: function(data, textStatus, jqXHR){
        console.log("setting lang from server", data);
        Cookies.set('lang', data, { expires: 7 });
        set_selected(data);
      },
      error: function(err){//if it doesn't work, default to English
        console.log(err)
        Cookies.set('lang',"en", { expires: 7 });
        set_selected("en");
      }
    });
  }else{
    set_selected(mylang);
  }
  //alert(mylang);
}

function set_selected(mylang){
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
  Cookies.set('lang', $('#sel_lang').val(), { expires: 7 });
  console.log("changing lang!", Cookies.get('lang'));
}