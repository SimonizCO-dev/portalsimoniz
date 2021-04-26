/**
 * Menu
 * ------------------
 */

//funcion para cargar y mostrar las opciones de menu
$.ajax({ 
  type: "POST",
  url: "/simoniz/index.php?r=menu/loadmenu", 
  dataType: 'json',
  success: function(data){
    if (data.length > 0) {
        $.each(data, function(i1) {
          //nivel 1
          id1 = data[i1]['id'];
          text1 = data[i1]['text'];
          long_text1 = data[i1]['long_text'];
          dd1 = data[i1]['dd'];
          children1 = data[i1]['children'];
          link1 = data[i1]['link'];

          if(long_text1 != null && long_text1 != ""){ tag_title1 = 'title="'+long_text1+'"'; }else{ tag_title1 = ''; }

          if(link1 == '#'){
            tag_onclick1 = '';
          }else{
            if(dd1 == '1'){ tag_onclick1 = 'onclick="loadershow();log('+id1+')"'; }else{ tag_onclick1 = 'onclick="log('+id1+')"';}
          }

          $("#navbar-nav-menu").append('<li id="me_li_'+id1+'"><a href="'+link1+'" id="me_a_'+id1+'" class="nav-link" '+tag_title1+' '+tag_onclick1+'>'+text1+'</a></li>');
          if (children1.length > 0) {
            //nivel 2
            $("#me_li_"+id1+"").addClass("nav-item dropdown");
            $("#me_a_"+id1+"").addClass("dropdown-toggle");
            $("#me_a_"+id1+"").attr("data-toggle", "dropdown");
            
            $("#me_li_"+id1+"").append('<ul class="dropdown-menu border-0 shadow" id="me_ul_'+id1+'"></ul>');
            $.each(children1, function(i2) {
              id2 = children1[i2]['id'];
              text2 = children1[i2]['text'];
              long_text2 = children1[i2]['long_text'];
              dd2 = children1[i2]['dd'];
              children2 = children1[i2]['children'];
              link2 = children1[i2]['link'];

              if(long_text2 != null & long_text2 != ""){ tag_title2 = 'title="'+long_text2+'"'; }else{ tag_title2 = ''; }
              
              if(link2 == '#'){
                tag_onclick2 = '';
              }else{
                if(dd2 == '1'){ tag_onclick2 = 'onclick="loadershow();log('+id2+')"'; }else{ tag_onclick2 = 'onclick="log('+id2+')"';}
              }

              $("#me_ul_"+id1+"").append('<li id="me_li_'+id2+'"><a href="'+link2+'" id="me_a_'+id2+'" '+tag_title2+' '+tag_onclick2+'>'+text2+'</a></li>');
              if (children2.length > 0) {
                //nivel 2
                $("#me_li_"+id2+"").addClass("dropdown-submenu dropdown-hover");
                $("#me_a_"+id2+"").addClass("dropdown-item dropdown-toggle small");

                $("#me_li_"+id2+"").append('<ul class="dropdown-menu border-0 shadow" id="me_ul_'+id2+'"></ul>');
                $.each(children2, function(i3) {
                  id3 = children2[i3]['id'];
                  text3 = children2[i3]['text'];
                  long_text3 = children2[i3]['long_text'];
                  dd3 = children2[i3]['dd'];
                  children3 = children2[i3]['children'];
                  link3 = children2[i3]['link'];
                  
                  if(long_text3 != null & long_text3 != ""){ tag_title3 = 'title="'+long_text3+'"'; }else{ tag_title3 = ''; }
                  
                  if(link3 == '#'){
                    tag_onclick3 = '';
                  }else{
                    if(dd3 == '1'){ tag_onclick3 = 'onclick="loadershow();log('+id3+')"'; }else{ tag_onclick3 = 'onclick="log('+id3+')"';}
                  }

                  $("#me_ul_"+id2+"").append('<li id="me_li_'+id3+'"><a href="'+link3+'" id="me_a_'+id3+'" '+tag_title3+' '+tag_onclick3+'>'+text3+'</a></li>');
                  if (children3.length > 0) {
                    //nivel 3
                    $("#me_li_"+id3+"").addClass("dropdown-submenu dropdown-hover");
                    $("#me_a_"+id3+"").addClass("dropdown-item dropdown-toggle small");
                    
                    $("#me_li_"+id3+"").append('<ul class="dropdown-menu border-0 shadow" id="me_ul_'+id3+'"></ul>');
                    $.each(children3, function(i4) {
                      id4 = children3[i4]['id'];
                      text4 = children3[i4]['text'];
                      long_text4 = children3[i4]['long_text'];
                      dd4 = children3[i4]['dd'];
                      children4 = children3[i4]['children'];
                      link4 = children3[i4]['link'];
                      
                      if(long_text4 != null && long_text4 != ""){ tag_title4 = 'title="'+long_text4+'"'; }else{ tag_title4 = ''; }
                      
                      if(link4 == '#'){
                        tag_onclick4 = '';
                      }else{
                        if(dd4 == '1'){ tag_onclick4 = 'onclick="loadershow();log('+id4+')"'; }else{ tag_onclick4 = 'onclick="log('+id4+')"';}
                      }

                      $("#me_ul_"+id3+"").append('<li id="me_li_'+id4+'" class="nav-item"><a href="'+link4+'" id="me_a_'+id4+'" class="dropdown-item small" '+tag_title4+' '+tag_onclick4+'>'+text4+'</a></li>'); 
                    });
                  }else{
                   $("#me_a_"+id3+"").addClass("dropdown-item small"); 
                  } 
                });
              }else{
                $("#me_a_"+id2+"").addClass("dropdown-item small");
              } 
            });
          }else{
            $("#me_li_"+id1+"").addClass("nav-item");
        }
      });
      //$("#navbar-nav-menu").fadeIn('fast');
    } 
  }
});
