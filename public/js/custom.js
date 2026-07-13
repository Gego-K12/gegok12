

$(document).ready(function(){
  $(".profile-click").click(function(){
    $(".user-dtl").toggle();
  });

  // "Apps & Add-ons" sidebar accordion: expands in place (chevron rotates)
  // instead of the hover-flyout every other dropdown uses, since it can
  // hold an arbitrary number of installed plugins.
  var $appsAddonsMenu = $(".apps-addons-menu");
  if ($appsAddonsMenu.find("li.active").length) {
    $appsAddonsMenu.removeClass("hidden");
    $appsAddonsMenu.closest("li").find(".apps-addons-chevron").addClass("is-open");
  }
  $(".apps-addons-toggle").click(function(e){
    e.preventDefault();
    var $li = $(this).closest("li");
    $li.find(".apps-addons-menu").toggleClass("hidden");
    $li.find(".apps-addons-chevron").toggleClass("is-open");
  });

  // Page-header "..." action menu (Export/Import/Id card/Attendance etc.)
  $(document).on("click", ".action-menu-toggle", function(e){
    e.preventDefault();
    e.stopPropagation();
    var $menu = $(this).siblings(".action-menu-dropdown");
    $(".action-menu-dropdown").not($menu).addClass("hidden");
    $menu.toggleClass("hidden");
  });
  $(document).on("click", function(e){
    if (!$(e.target).closest(".action-menu-toggle, .action-menu-dropdown").length) {
      $(".action-menu-dropdown").addClass("hidden");
    }
  });
});


  function show() {
    if($('.create_event').hasclass('hidden'))
    {
      $('.create_event').removeclass('hidden').addclass('block');
    }
    else 
    {
      $('.create_event').removeclass('block').addclass('hidden');
    }
  }


   function showsidebar(id){
    if($('#'+id).hasClass('hidden')){
      $('#'+id).removeClass('hidden').addClass('block');
    }
      else
      {
      $('#'+id).removeClass('block').addClass('hidden');
    }
  }
