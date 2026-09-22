(function () {
	"use strict";

	function initializeArcnSliders() {
		const sliders = document.querySelectorAll(
			".arcn-hero-slider:not([data-arcn-initialized])"
		);

		sliders.forEach(function (slider) {
			initializeArcnSlider(slider);
		});
	}

	function initializeArcnSlider(slider) {
		const slides = Array.from(
			slider.querySelectorAll(".arcn-slider-slide")
		);

		if (slides.length < 2) {
			slider.setAttribute(
				"data-arcn-initialized",
				"true"
			);

			return;
		}

		const thumbnails = Array.from(
			slider.querySelectorAll(
				".arcn-slider-thumbnail"
			)
		);

		const sourceText = slider.querySelector(
			".arcn-slider-source-text"
		);

		const currentNumber = slider.querySelector(
			".arcn-slider-current"
		);

		const progressBar = slider.querySelector(
			".arcn-slider-progress-bar"
		);

		const previousButton = slider.querySelector(
			".arcn-slider-prev"
		);

		const nextButton = slider.querySelector(
			".arcn-slider-next"
		);

		const toggleButton = slider.querySelector(
			".arcn-slider-toggle"
		);

		const delayAttribute = Number.parseInt(
			slider.getAttribute(
				"data-autoplay-delay"
			),
			10
		);

		const autoplayDelay = Number.isFinite(
			delayAttribute
		)
			? Math.max(3000, delayAttribute)
			: 5500;

		/*
		 * First page-load cycle is half the
		 * normal duration, matching the
		 * existing ARCN slider behaviour.
		 */
		const initialSlideDelay =
			autoplayDelay / 2;

		let isInitialSlideCycle = true;

		const reducedMotionQuery =
			window.matchMedia(
				"(prefers-reduced-motion: reduce)"
			);

		let currentIndex = 0;
		let elapsedTime = 0;
		let previousFrameTime = null;
		let animationFrameId = 0;

		let manualPaused = false;
		let hoverPaused = false;
		let focusPaused = false;
		let touchPaused = false;
		let sliderIsVisible = true;

		let touchStartX = 0;
		let touchStartY = 0;
		let touchResumeTimer = 0;

		slider.setAttribute(
			"data-arcn-initialized",
			"true"
		);

		function canAutoplay() {
			return (
				!document.hidden &&
				sliderIsVisible &&
				!manualPaused &&
				!hoverPaused &&
				!focusPaused &&
				!touchPaused &&
				!reducedMotionQuery.matches
			);
		}

		function getCurrentDelay() {
			return isInitialSlideCycle
				? initialSlideDelay
				: autoplayDelay;
		}

		function updateToggleButton() {
			if (!toggleButton) {
				return;
			}

			toggleButton.classList.toggle(
				"is-paused",
				manualPaused
			);

			toggleButton.setAttribute(
				"aria-label",
				manualPaused
					? "Play slideshow"
					: "Pause slideshow"
			);

			toggleButton.setAttribute(
				"aria-pressed",
				manualPaused
					? "true"
					: "false"
			);
		}

		function updateProgress() {
			if (!progressBar) {
				return;
			}

			const currentDelay =
				getCurrentDelay();

			const progress = Math.min(
				1,
				Math.max(
					0,
					elapsedTime / currentDelay
				)
			);

			progressBar.style.transform =
				"scaleX(" + progress + ")";
		}

		function updateSlideStates() {
			slides.forEach(
				function (slide, index) {
					const isActive =
						index === currentIndex;

					slide.classList.toggle(
						"is-active",
						isActive
					);

					slide.setAttribute(
						"aria-hidden",
						isActive
							? "false"
							: "true"
					);
				}
			);

			thumbnails.forEach(
				function (thumbnail, index) {
					const isActive =
						index === currentIndex;

					thumbnail.classList.toggle(
						"is-active",
						isActive
					);

					thumbnail.setAttribute(
						"aria-current",
						isActive
							? "true"
							: "false"
					);
				}
			);
		}

		function updateSlideInformation() {
			const activeSlide =
				slides[currentIndex];

			if (
				sourceText &&
				activeSlide
			) {
				sourceText.textContent =
					activeSlide.getAttribute(
						"data-source"
					) || "";
			}

			if (currentNumber) {
				currentNumber.textContent =
					String(
						currentIndex + 1
					).padStart(
						2,
						"0"
					);
			}
		}

		function showSlide(
			index,
			resetProgress
		) {
			currentIndex =
				(
					index +
					slides.length
				) % slides.length;

			if (
				isInitialSlideCycle &&
				currentIndex !== 0
			) {
				isInitialSlideCycle = false;
			}

			updateSlideStates();
			updateSlideInformation();

			if (resetProgress !== false) {
				elapsedTime = 0;
				updateProgress();
			}
		}

		function showNextSlide(
			resetProgress
		) {
			showSlide(
				currentIndex + 1,
				resetProgress
			);
		}

		function showPreviousSlide(
			resetProgress
		) {
			showSlide(
				currentIndex - 1,
				resetProgress
			);
		}

		function resetFrameTiming() {
			previousFrameTime = null;
		}

		function stopAnimationLoop() {
			if (!animationFrameId) {
				return;
			}

			window.cancelAnimationFrame(
				animationFrameId
			);

			animationFrameId = 0;
			previousFrameTime = null;
		}

		function startAnimationLoop() {
			if (
				animationFrameId ||
				!canAutoplay()
			) {
				return;
			}

			previousFrameTime = null;

			animationFrameId =
				window.requestAnimationFrame(
					animationLoop
				);
		}

		function refreshAutoplayState() {
			if (canAutoplay()) {
				startAnimationLoop();
			} else {
				stopAnimationLoop();
			}
		}

		function animationLoop(timestamp) {
			animationFrameId = 0;

			if (!canAutoplay()) {
				previousFrameTime = null;
				return;
			}

			if (
				previousFrameTime === null
			) {
				previousFrameTime =
					timestamp;
			}

			const frameDifference =
				Math.min(
					100,
					Math.max(
						0,
						timestamp -
						previousFrameTime
					)
				);

			previousFrameTime = timestamp;

			elapsedTime +=
				frameDifference;

			const currentDelay =
				getCurrentDelay();

			if (
				elapsedTime >=
				currentDelay
			) {
				if (isInitialSlideCycle) {
					isInitialSlideCycle =
						false;
				}

				showNextSlide(false);

				elapsedTime = 0;
			}

			updateProgress();

			animationFrameId =
				window.requestAnimationFrame(
					animationLoop
				);
		}

		function toggleManualPause() {
			manualPaused =
				!manualPaused;

			updateToggleButton();
			refreshAutoplayState();
		}

		if (previousButton) {
			previousButton.addEventListener(
				"click",
				function () {
					showPreviousSlide(true);

					resetFrameTiming();

					refreshAutoplayState();
				}
			);
		}

		if (nextButton) {
			nextButton.addEventListener(
				"click",
				function () {
					showNextSlide(true);

					resetFrameTiming();

					refreshAutoplayState();
				}
			);
		}

		if (toggleButton) {
			toggleButton.addEventListener(
				"click",
				toggleManualPause
			);
		}

		thumbnails.forEach(
			function (thumbnail) {
				thumbnail.addEventListener(
					"click",
					function () {
						const slideIndex =
							Number.parseInt(
								thumbnail.getAttribute(
									"data-slide"
								),
								10
							);

						if (
							!Number.isInteger(
								slideIndex
							)
						) {
							return;
						}

						showSlide(
							slideIndex,
							true
						);

						resetFrameTiming();

						refreshAutoplayState();
					}
				);
			}
		);

		slider.addEventListener(
			"mouseenter",
			function () {
				hoverPaused = true;

				refreshAutoplayState();
			}
		);

		slider.addEventListener(
			"mouseleave",
			function () {
				hoverPaused = false;

				refreshAutoplayState();
			}
		);

		slider.addEventListener(
			"focusin",
			function () {
				focusPaused = true;

				refreshAutoplayState();
			}
		);

		slider.addEventListener(
			"focusout",
			function (event) {
				if (
					!event.relatedTarget ||
					!slider.contains(
						event.relatedTarget
					)
				) {
					focusPaused = false;

					refreshAutoplayState();
				}
			}
		);

		slider.addEventListener(
			"keydown",
			function (event) {
				if (
					event.target.closest(
						"button, a, input, select, textarea"
					)
				) {
					return;
				}

				if (
					event.key ===
					"ArrowRight"
				) {
					event.preventDefault();

					showNextSlide(true);

					resetFrameTiming();

					refreshAutoplayState();

					return;
				}

				if (
					event.key ===
					"ArrowLeft"
				) {
					event.preventDefault();

					showPreviousSlide(true);

					resetFrameTiming();

					refreshAutoplayState();

					return;
				}

				if (
					event.key === " " ||
					event.key ===
					"Spacebar"
				) {
					event.preventDefault();

					toggleManualPause();
				}
			}
		);

		slider.addEventListener(
			"touchstart",
			function (event) {
				if (touchResumeTimer) {
					window.clearTimeout(
						touchResumeTimer
					);
				}

				touchPaused = true;

				refreshAutoplayState();

				if (
					event.touches &&
					event.touches[0]
				) {
					touchStartX =
						event.touches[0]
							.clientX;

					touchStartY =
						event.touches[0]
							.clientY;
				}
			},
			{
				passive: true
			}
		);

		slider.addEventListener(
			"touchend",
			function (event) {
				let touchEndX =
					touchStartX;

				let touchEndY =
					touchStartY;

				if (
					event.changedTouches &&
					event.changedTouches[0]
				) {
					touchEndX =
						event.changedTouches[0]
							.clientX;

					touchEndY =
						event.changedTouches[0]
							.clientY;
				}

				const horizontalDistance =
					touchEndX -
					touchStartX;

				const verticalDistance =
					touchEndY -
					touchStartY;

				const isHorizontalSwipe =
					Math.abs(
						horizontalDistance
					) > 40 &&
					Math.abs(
						horizontalDistance
					) >
					Math.abs(
						verticalDistance
					);

				if (isHorizontalSwipe) {
					if (
						horizontalDistance <
						0
					) {
						showNextSlide(true);
					} else {
						showPreviousSlide(
							true
						);
					}
				}

				touchResumeTimer =
					window.setTimeout(
						function () {
							touchPaused =
								false;

							refreshAutoplayState();
						},
						900
					);
			},
			{
				passive: true
			}
		);

		slider.addEventListener(
			"touchcancel",
			function () {
				touchPaused = false;

				refreshAutoplayState();
			},
			{
				passive: true
			}
		);

		document.addEventListener(
			"visibilitychange",
			function () {
				resetFrameTiming();

				refreshAutoplayState();
			}
		);

		if (
			"IntersectionObserver" in
			window
		) {
			const observer =
				new IntersectionObserver(
					function (entries) {
						entries.forEach(
							function (entry) {
								sliderIsVisible =
									entry.isIntersecting &&
									entry.intersectionRatio >
									0;

								refreshAutoplayState();
							}
						);
					},
					{
						threshold: [
							0,
							0.05
						]
					}
				);

			observer.observe(slider);
		}

		function handleMotionPreferenceChange() {
			if (
				reducedMotionQuery.matches
			) {
				elapsedTime = 0;

				updateProgress();
			}

			refreshAutoplayState();
		}

		if (
			typeof reducedMotionQuery
				.addEventListener ===
			"function"
		) {
			reducedMotionQuery
				.addEventListener(
					"change",
					handleMotionPreferenceChange
				);
		} else if (
			typeof reducedMotionQuery
				.addListener ===
			"function"
		) {
			reducedMotionQuery.addListener(
				handleMotionPreferenceChange
			);
		}

		/*
		 * Initial state.
		 */
		updateToggleButton();

		showSlide(
			0,
			true
		);

		refreshAutoplayState();
	}

	if (
		document.readyState ===
		"loading"
	) {
		document.addEventListener(
			"DOMContentLoaded",
			initializeArcnSliders,
			{
				once: true
			}
		);
	} else {
		initializeArcnSliders();
	}
}());