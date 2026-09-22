(function () {
	"use strict";

	/* =========================================================
	   ARCN STANDARD PAGE NAVIGATION
	   ---------------------------------------------------------
	   PHP creates:
	   - section IDs
	   - navigation links

	   This file only handles:
	   - smooth scrolling
	   - active navigation state
	   - URL hash updates
	   ========================================================= */


	/* =========================================================
	   HELPERS
	   ========================================================= */

	function getNavigationLinks() {
		return Array.from(
			document.querySelectorAll(
				".arcn-page-nav__link"
			)
		);
	}


	function getSections() {
		return Array.from(
			document.querySelectorAll(
				".arcn-content-section[id]"
			)
		);
	}


	function getTargetFromLink(link) {

		if (
			!(link instanceof HTMLAnchorElement)
		) {
			return null;
		}


		const href =
			link.getAttribute("href");


		if (
			!href ||
			!href.startsWith("#")
		) {
			return null;
		}


		const id =
			href.slice(1);


		if (!id) {
			return null;
		}


		return document.getElementById(
			id
		);
	}


	/* =========================================================
	   ACTIVE LINK
	   ========================================================= */

	function setActiveLink(
		activeId
	) {

		// Excluded sections belong to the preceding visible navigation item.
		const sections = getSections();
		let sectionIndex = sections.findIndex(function (section) {
			return section.id === activeId;
		});
		while (sectionIndex >= 0 && sections[sectionIndex].classList.contains("arcn-nav-excluded")) {
			sectionIndex--;
			activeId = sectionIndex >= 0 ? sections[sectionIndex].id : "";
		}

		const links =
			getNavigationLinks();


		links.forEach(
			function (link) {

				const href =
					link.getAttribute(
						"href"
					);


				const isActive =
					href ===
					"#" + activeId;


				link.classList.toggle(
					"is-active",
					isActive
				);


				if (isActive) {

					link.setAttribute(
						"aria-current",
						"true"
					);

				} else {

					link.removeAttribute(
						"aria-current"
					);
				}
			}
		);
	}


	/* =========================================================
	   SMOOTH SCROLL
	   ========================================================= */

	function scrollToSection(
		target
	) {

		if (
			!(target instanceof Element)
		) {
			return;
		}


		target.scrollIntoView(
			{
				behavior: window.matchMedia(
					"(prefers-reduced-motion: reduce)"
				).matches ? "instant" : "smooth",
				block: "start"
			}
		);
	}


	/* =========================================================
	   CLICK HANDLING
	   ========================================================= */

	function initialiseNavigationClicks() {

		const links =
			getNavigationLinks();


		links.forEach(
			function (link) {

				link.addEventListener(
					"click",
					function (event) {

						const target =
							getTargetFromLink(
								link
							);


						if (!target) {
							return;
						}


						event.preventDefault();


						const id =
							target.id;


						setActiveLink(
							id
						);


						scrollToSection(
							target
						);


						if (
							window.history &&
							window.history.replaceState
						) {

							window.history.replaceState(
								null,
								"",
								"#" + id
							);
						}
					}
				);
			}
		);
	}


	/* =========================================================
	   ACTIVE SECTION ON SCROLL
	   ========================================================= */

	function initialiseSectionObserver() {

		const sections =
			getSections();


		if (
			sections.length === 0
		) {
			return;
		}


		if (
			!("IntersectionObserver" in window)
		) {
			return;
		}


		const observer =
			new IntersectionObserver(
				function (entries) {

					const visibleEntries =
						entries
							.filter(
								function (
									entry
								) {

									return (
										entry.isIntersecting
									);
								}
							)
							.sort(
								function (
									a,
									b
								) {

									return (
										a.boundingClientRect.top -
										b.boundingClientRect.top
									);
								}
							);


					if (
						visibleEntries.length === 0
					) {
						return;
					}


					const section =
						visibleEntries[0]
							.target;


					if (!section.id) {
						return;
					}


					setActiveLink(
						section.id
					);
				},
				{
					root: null,

					/*
					 * Makes the active state switch
					 * around the upper-middle portion
					 * of the viewport.
					 */
					rootMargin:
						"-20% 0px -65% 0px",

					threshold: 0
				}
			);


		sections.forEach(
			function (section) {

				observer.observe(
					section
				);
			}
		);
	}


	/* =========================================================
	   INITIAL HASH
	   ========================================================= */

	function initialiseCurrentHash() {

		const hash =
			window.location.hash;


		if (!hash) {
			return;
		}


		const id =
			hash.slice(1);


		if (!id) {
			return;
		}


		const target =
			document.getElementById(
				id
			);


		if (!target) {
			return;
		}


		setActiveLink(
			id
		);
	}


	/* =========================================================
	   FALLBACK INITIAL ACTIVE ITEM
	   ========================================================= */

	function initialiseFirstSection() {

		const activeLink =
			document.querySelector(
				".arcn-page-nav__link.is-active"
			);


		if (activeLink) {
			return;
		}


		const sections =
			getSections();


		if (
			sections.length === 0
		) {
			return;
		}


		if (
			window.location.hash
		) {
			return;
		}


		setActiveLink(
			sections[0].id
		);
	}


	/* =========================================================
	   START
	   ========================================================= */

	function initialiseArcnPageNavigation() {

		const navigation =
			document.querySelector(
				".arcn-page-nav"
			);


		if (!navigation) {
			return;
		}


		initialiseNavigationClicks();

		initialiseSectionObserver();

		initialiseCurrentHash();

		initialiseFirstSection();
	}


	if (
		document.readyState ===
		"loading"
	) {

		document.addEventListener(
			"DOMContentLoaded",
			initialiseArcnPageNavigation,
			{
				once: true
			}
		);

	} else {

		initialiseArcnPageNavigation();
	}
})();
