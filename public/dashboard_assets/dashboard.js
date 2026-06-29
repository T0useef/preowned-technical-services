$(function () {
  const $sidebar = $("#sidebar");
  const $overlay = $("#sidebarOverlay");
  const isMobile = () => window.matchMedia("(max-width: 991.98px)").matches;

  function closeSidebar() {
    $sidebar.removeClass("show");
    $overlay.removeClass("show");
    $("body").removeClass("overflow-hidden");
  }

  $("#toggleSidebar").on("click", function () {
    $sidebar.toggleClass("show");
    $overlay.toggleClass("show");
    $("body").toggleClass("overflow-hidden");
  });

  $overlay.on("click", closeSidebar);

  $(window).on("resize", function () {
    if (!isMobile()) {
      closeSidebar();
    }
  });
});