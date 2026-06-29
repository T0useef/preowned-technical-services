$(function () {
    var $window = $(window);
    var $navbar = $(".site-navbar");
    var words = ["Construction", "Maintenance", "Plumbing"];
    var $word = $("#rotating-word");
    var $backgrounds = $(".hero-bg");
    var index = 0;
    var typeSpeedMs = 95;
    var deleteSpeedMs = 70;
    var holdDelayMs = 3000;
    var nextWordDelayMs = 250;
  
    function setNavbarState() {
      if ($window.scrollTop() > 40) {
        $navbar.addClass("scrolled");
      } else {
        $navbar.removeClass("scrolled");
      }
    }
  
    var navbarTicking = false;
    function scheduleNavbarState() {
      if (navbarTicking) {
        return;
      }
      navbarTicking = true;
      window.requestAnimationFrame(function () {
        setNavbarState();
        navbarTicking = false;
      });
    }
  
    setNavbarState();
    window.addEventListener("scroll", scheduleNavbarState, { passive: true });
    window.addEventListener("resize", scheduleNavbarState, { passive: true });
  
    function initServicesTicker() {
      var $tickerSection = $(".services-ticker-section");
      if (!$tickerSection.length) {
        return;
      }
  
      $tickerSection.each(function () {
        var $section = $(this);
        var $track = $section.find(".services-ticker-track");
        var $baseGroup = $track.find(".services-ticker-group").first();
        if (!$track.length || !$baseGroup.length) {
          return;
        }
  
        var $baseItems = $baseGroup.find(".services-ticker-item").clone();
        if (!$baseItems.length) {
          return;
        }
  
        var baseMarkup = $("<div></div>").append($baseItems).html();
        $track.empty();
  
        var $measureGroup = $("<div class='services-ticker-group'></div>").html(baseMarkup);
        $track.append($measureGroup);
  
        var baseWidth = $measureGroup[0].getBoundingClientRect().width;
        var sectionStyle = window.getComputedStyle($section[0]);
        var groupGap = parseFloat(sectionStyle.getPropertyValue("--ticker-group-gap")) || 0;
        var cycleWidth = baseWidth + groupGap;
        if (!cycleWidth) {
          return;
        }
  
        var viewportWidth = $section[0].clientWidth || window.innerWidth || 1200;
        var copiesNeeded = Math.max(2, Math.ceil((viewportWidth * 2.8) / cycleWidth));
  
        $track.empty();
        for (var i = 0; i < copiesNeeded; i += 1) {
          var $group = $("<div class='services-ticker-group'></div>").html(baseMarkup);
          if (i > 0) {
            $group.attr("aria-hidden", "true");
          }
          $track.append($group);
        }
  
        var pxPerSecond = 82;
        var durationSec = Math.max(9, Math.min(42, cycleWidth / pxPerSecond));
        $track[0].style.setProperty("--ticker-shift", cycleWidth.toFixed(2) + "px");
        $track[0].style.setProperty("--ticker-duration", durationSec.toFixed(2) + "s");
      });
    }
  
    initServicesTicker();
    var tickerResizeTimer = null;
    function scheduleTickerRefresh() {
      if (tickerResizeTimer) {
        clearTimeout(tickerResizeTimer);
      }
      tickerResizeTimer = setTimeout(initServicesTicker, 160);
    }
  
    window.addEventListener("resize", scheduleTickerRefresh, { passive: true });
    window.addEventListener("load", scheduleTickerRefresh, { passive: true });
  
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(scheduleTickerRefresh);
    }
  
    if ("ResizeObserver" in window) {
      var tickerObserver = new ResizeObserver(function () {
        scheduleTickerRefresh();
      });
      $(".services-ticker-section").each(function () {
        tickerObserver.observe(this);
      });
    }
  
    if ($word.length && $backgrounds.length >= words.length) {
      var currentText = words[index];
      $word.text(currentText);
      $word.removeClass("is-hidden").addClass("is-visible");
      $backgrounds.removeClass("is-active");
      $backgrounds.eq(index).addClass("is-active");
  
      function deleteStep() {
        if (currentText.length > 0) {
          currentText = currentText.slice(0, -1);
          $word.text(currentText);
          setTimeout(deleteStep, deleteSpeedMs);
        } else {
          index = (index + 1) % words.length;
          $backgrounds.removeClass("is-active");
          $backgrounds.eq(index).addClass("is-active");
          setTimeout(typeStep, nextWordDelayMs);
        }
      }
  
      function typeStep() {
        var targetWord = words[index];
        if (currentText.length < targetWord.length) {
          currentText = targetWord.slice(0, currentText.length + 1);
          $word.text(currentText);
          setTimeout(typeStep, typeSpeedMs);
        } else {
          setTimeout(deleteStep, holdDelayMs);
        }
      }
  
      setTimeout(deleteStep, holdDelayMs);
    }
  
    var $about = $("#about");
    if ($about.length) {
      var aboutEl = $about[0];
      function markAboutInView() {
        $about.addClass("is-inview");
      }
      function aboutInViewport() {
        var rect = aboutEl.getBoundingClientRect();
        var vh = window.innerHeight || document.documentElement.clientHeight;
        return rect.top < vh * 0.9 && rect.bottom > 0;
      }
      if (aboutInViewport()) {
        markAboutInView();
      } else if ("IntersectionObserver" in window) {
        var aboutObserver = new IntersectionObserver(
          function (entries) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                markAboutInView();
                aboutObserver.disconnect();
              }
            });
          },
          { rootMargin: "0px 0px -8% 0px", threshold: 0.12 }
        );
        aboutObserver.observe(aboutEl);
      } else {
        markAboutInView();
      }
    }
  
    var $workCarousel = $("#workCarousel");
    if ($workCarousel.length) {
      var $workCards = $workCarousel.find(".work-card");
      var slotClasses = [
        "slot-far-left",
        "slot-left",
        "slot-center",
        "slot-right",
        "slot-far-right",
        "slot-off-right",
        "slot-off-left"
      ];
      var centerSlotIndex = 2;
      var rotateIntervalMs = 2600;
      var resumeAfterClickMs = 3200;
      var rotateStep = 0;
      var workIntervalId = null;
      var workResumeTimeoutId = null;
      var isReducedMotion =
        window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  
      function normalizeStep(value) {
        return ((value % slotClasses.length) + slotClasses.length) % slotClasses.length;
      }
  
      function applyWorkSlots() {
        $workCards.each(function (idx) {
          var slot = slotClasses[normalizeStep(idx + rotateStep)];
          $(this)
            .removeClass("slot-far-left slot-left slot-center slot-right slot-far-right slot-off-right slot-off-left")
            .addClass(slot);
        });
      }
  
      function advanceWork() {
        rotateStep = normalizeStep(rotateStep + slotClasses.length - 1);
        applyWorkSlots();
      }
  
      function stopWorkAuto() {
        if (workIntervalId) {
          clearInterval(workIntervalId);
          workIntervalId = null;
        }
        if (workResumeTimeoutId) {
          clearTimeout(workResumeTimeoutId);
          workResumeTimeoutId = null;
        }
      }
  
      function startWorkAuto() {
        if (isReducedMotion) {
          return;
        }
        stopWorkAuto();
        workIntervalId = setInterval(advanceWork, rotateIntervalMs);
      }
  
      function focusCardAsMain(cardIdx) {
        rotateStep = normalizeStep(centerSlotIndex - cardIdx);
        applyWorkSlots();
      }
  
      function scrollToWorkSection() {
        var targetEl = $workCarousel[0] || document.getElementById("work");
        if (!targetEl) {
          return;
        }
        var rect = targetEl.getBoundingClientRect();
        var viewportH = window.innerHeight || document.documentElement.clientHeight;
        var targetY = window.scrollY + rect.top + rect.height / 2 - viewportH / 2;
        var maxScroll = Math.max(
          0,
          document.documentElement.scrollHeight - viewportH
        );
        targetY = Math.max(0, Math.min(targetY, maxScroll));
  
        window.scrollTo({
          top: targetY,
          behavior: isReducedMotion ? "auto" : "smooth"
        });
      }
  
      if ($workCards.length >= slotClasses.length) {
        applyWorkSlots();
        $workCards.attr({ role: "button", tabindex: "0" });
        $workCards.on("click", function () {
          focusCardAsMain($workCards.index(this));
          scrollToWorkSection();
          if (!isReducedMotion) {
            stopWorkAuto();
            workResumeTimeoutId = setTimeout(function () {
              startWorkAuto();
            }, resumeAfterClickMs);
          }
        });
        $workCards.on("keydown", function (event) {
          if (event.key === "Enter" || event.key === " ") {
            event.preventDefault();
            $(this).trigger("click");
          }
        });
        startWorkAuto();
      } else {
        $workCards.each(function () {
          $(this).removeClass("slot-far-left slot-left slot-center slot-right slot-far-right slot-off-right slot-off-left");
        });
      }
    }
  
    function initPartnersSlider() {
      var $viewport = $("#partnersViewport");
      var $track = $("#partnersTrack");
      if (!$viewport.length || !$track.length) {
        return null;
      }
  
      var isReducedMotion =
        window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
  
      var $baseItems = $track.find(".partner-logo-card").clone();
      if (!$baseItems.length) {
        return null;
      }
  
      $track.empty().append($baseItems);
      $baseItems.each(function () {
        $track.append($(this).clone().attr("aria-hidden", "true"));
      });
  
      var index = 0;
      var stepInterval = null;
      var isTransitioning = false;
  
      function getStepWidth() {
        var cards = $track.find(".partner-logo-card");
        if (cards.length < 2) {
          return cards.first().outerWidth(true) || 0;
        }
        var left0 = cards.eq(0)[0].offsetLeft;
        var left1 = cards.eq(1)[0].offsetLeft;
        return Math.max(0, left1 - left0);
      }
  
      function applyStepPosition() {
        var stepWidth = getStepWidth();
        if (!stepWidth) {
          return;
        }
        $track.css("transform", "translate3d(" + (-index * stepWidth) + "px, 0, 0)");
      }
  
      function stepOnce() {
        if (isTransitioning || isReducedMotion) {
          return;
        }
        isTransitioning = true;
        index += 1;
        applyStepPosition();
      }
  
      $track.off("transitionend.partners").on("transitionend.partners", function () {
        var originalCount = $baseItems.length;
        if (index >= originalCount) {
          $track.css("transition", "none");
          index = 0;
          applyStepPosition();
          // Force reflow before restoring transition to avoid visual jump.
          void $track[0].offsetWidth;
          $track.css("transition", "");
        }
        isTransitioning = false;
      });
  
      function start() {
        if (isReducedMotion) {
          return;
        }
        if (stepInterval) {
          clearInterval(stepInterval);
        }
        stepInterval = setInterval(stepOnce, 2500);
      }
  
      function refresh() {
        isTransitioning = false;
        applyStepPosition();
      }
  
      refresh();
      start();
  
      return {
        refresh: refresh
      };
    }
  
    var partnersController = initPartnersSlider();
    var partnersResizeTimer = null;
    function schedulePartnersRefresh() {
      if (!partnersController) {
        return;
      }
      if (partnersResizeTimer) {
        clearTimeout(partnersResizeTimer);
      }
      partnersResizeTimer = setTimeout(function () {
        partnersController.refresh();
      }, 120);
    }
    window.addEventListener("resize", schedulePartnersRefresh, { passive: true });
  
    function initFeedbackDeck() {
      var $deck = $("#feedbackDeck");
      if (!$deck.length) {
        return;
      }
  
      var $cards = $deck.find(".feedback-card");
      var cardCount = $cards.length;
      if (cardCount < 2) {
        return;
      }
      var slotClasses = ["slot-front", "slot-next", "slot-third", "slot-fourth"];
      var rotateStep = 0;
      var isReducedMotion =
        window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
      var stepEveryMs = 2800;
      var fadeOutMs = 420;
      var tickId = null;
  
      function normalizeStep(value) {
        return ((value % cardCount) + cardCount) % cardCount;
      }
  
      function getRelativePosition(idx) {
        return normalizeStep(idx + rotateStep);
      }
  
      function applySlots() {
        $cards.each(function (idx) {
          var relative = getRelativePosition(idx);
          var slot = relative < slotClasses.length ? slotClasses[relative] : "slot-hidden";
          $(this)
            .removeClass("slot-front slot-next slot-third slot-fourth slot-hidden is-fading is-revealing")
            .addClass(slot);
        });
      }
  
      applySlots();
  
      if (!isReducedMotion) {
        tickId = setInterval(function () {
          var $frontCard = $cards.filter(".slot-front").first();
          if ($frontCard.length) {
            $frontCard.addClass("is-fading");
          }
          setTimeout(function () {
            rotateStep = normalizeStep(rotateStep + cardCount - 1);
            applySlots();
            var $newFront = $cards.filter(".slot-front").first();
            if ($newFront.length) {
              $newFront.addClass("is-revealing");
              setTimeout(function () {
                $newFront.removeClass("is-revealing");
              }, 320);
            }
          }, fadeOutMs);
        }, stepEveryMs);
      }
  
      return {
        destroy: function () {
          if (tickId) {
            clearInterval(tickId);
          }
        }
      };
    }
  
    initFeedbackDeck();
  
    function initProjectPopup() {
      var $projectCards = $(".project-card");
      if (!$projectCards.length) {
        return;
      }
  
      var modalId = "projectDetailPopup";
      var $popup = $("#" + modalId);
      if (!$popup.length) {
        var modalMarkup =
          '<div class="project-popup" id="' +
          modalId +
          '" aria-hidden="true">' +
          '<div class="project-popup-backdrop" data-close-popup="true"></div>' +
          '<div class="project-popup-dialog" role="dialog" aria-modal="true" aria-labelledby="projectPopupTitle">' +
          '<button type="button" class="project-popup-close" aria-label="Close project details" data-close-popup="true"><i class="fa-solid fa-xmark"></i></button>' +
          '<div class="project-popup-media-wrap"><img class="project-popup-media" alt="" loading="lazy"></div>' +
          '<div class="project-popup-content">' +
          '<h3 id="projectPopupTitle" class="project-popup-title"></h3>' +
          '<div class="project-popup-meta"></div>' +
          '<p class="project-popup-description mb-0"></p>' +
          "</div>" +
          "</div>" +
          "</div>";
        $("body").append(modalMarkup);
        $popup = $("#" + modalId);
      }
  
      var $title = $popup.find(".project-popup-title");
      var $meta = $popup.find(".project-popup-meta");
      var $desc = $popup.find(".project-popup-description");
      var $media = $popup.find(".project-popup-media");
      var lastFocusEl = null;
  
      function buildDescription(title, metaText) {
        return (
          title +
          " is delivered with detailed planning, premium workmanship, and clear execution milestones. " +
          "This project reflects our modern technical standards across " +
          (metaText || "all delivery stages") +
          "."
        );
      }
  
      function openPopup($card) {
        var imageSrc = $card.find(".project-card-img").attr("src") || "";
        var imageAlt = $card.find(".project-card-img").attr("alt") || "Project image";
        var title = $.trim($card.find("h3").first().text());
        var metaItems = [];
        $card.find(".project-card-overlay p").each(function () {
          var text = $.trim($(this).text());
          if (text) {
            metaItems.push(text);
          }
        });
  
        $title.text(title || "Project Details");
        $meta.empty();
        metaItems.forEach(function (item) {
          $meta.append('<span class="project-popup-meta-item">' + item + "</span>");
        });
        $desc.text(buildDescription(title || "This project", metaItems.join(", ")));
        $media.attr("src", imageSrc).attr("alt", imageAlt);
  
        lastFocusEl = document.activeElement;
        $popup.addClass("is-open").attr("aria-hidden", "false");
        $("body").addClass("project-popup-open");
        $popup.find(".project-popup-close").trigger("focus");
      }
  
      function closePopup() {
        $popup.removeClass("is-open").attr("aria-hidden", "true");
        $("body").removeClass("project-popup-open");
        if (lastFocusEl && typeof lastFocusEl.focus === "function") {
          lastFocusEl.focus();
        }
      }
  
      $projectCards.attr({ role: "button", tabindex: "0" });
      $projectCards.off("click.projectPopup").on("click.projectPopup", function () {
        openPopup($(this));
      });
      $projectCards.off("keydown.projectPopup").on("keydown.projectPopup", function (event) {
        if (event.key === "Enter" || event.key === " ") {
          event.preventDefault();
          openPopup($(this));
        }
      });
  
      $popup.off("click.closePopup").on("click.closePopup", "[data-close-popup='true']", function () {
        closePopup();
      });
  
      $(document).off("keydown.projectPopup").on("keydown.projectPopup", function (event) {
        if (event.key === "Escape" && $popup.hasClass("is-open")) {
          closePopup();
        }
      });
    }
  
    initProjectPopup();
  
    function initTestimonialsSection() {
      var $revealItems = $(".ux-reveal");
      var $counterItems = $(".value-counter");
      if (!$revealItems.length && !$counterItems.length) {
        return;
      }
  
      function animateCounter(el) {
        if (el.dataset.animated === "true") {
          return;
        }
        var target = parseInt(el.dataset.target, 10);
        if (isNaN(target)) {
          return;
        }
        el.dataset.animated = "true";
        var suffix = el.dataset.suffix || "";
        var start = 0;
        var duration = 1200;
        var startTime = null;
  
        function tick(timestamp) {
          if (!startTime) {
            startTime = timestamp;
          }
          var progress = Math.min((timestamp - startTime) / duration, 1);
          var value = Math.floor(start + (target - start) * progress);
          el.textContent = value + suffix;
          if (progress < 1) {
            window.requestAnimationFrame(tick);
          } else {
            el.textContent = target + suffix;
          }
        }
        window.requestAnimationFrame(tick);
      }
  
      if ("IntersectionObserver" in window) {
        var revealObserver = new IntersectionObserver(
          function (entries, observer) {
            entries.forEach(function (entry) {
              if (entry.isIntersecting) {
                entry.target.classList.add("is-visible");
                if (entry.target.classList.contains("value-counter")) {
                  animateCounter(entry.target);
                } else {
                  var nestedCounters = entry.target.querySelectorAll(".value-counter");
                  nestedCounters.forEach(function (counterEl) {
                    animateCounter(counterEl);
                  });
                }
                observer.unobserve(entry.target);
              }
            });
          },
          { threshold: 0.16, rootMargin: "0px 0px -8% 0px" }
        );
  
        $revealItems.each(function () {
          revealObserver.observe(this);
        });
        $counterItems.each(function () {
          revealObserver.observe(this);
        });
      } else {
        $revealItems.addClass("is-visible");
        $counterItems.each(function () {
          animateCounter(this);
        });
      }
    }
  
    initTestimonialsSection();
  });
  