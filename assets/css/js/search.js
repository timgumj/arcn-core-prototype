(function () {
	"use strict";


	const HEADER_INNER_SELECTOR =
		".arcn-site-header__inner";

	const NAV_SELECTOR =
		".arcn-primary-nav";

	const MOBILE_MENU_SELECTOR =
		".arcn-mobile-menu";


	/* =====================================================
	   HOME URL
	   ===================================================== */

	function getHomeUrl() {

		const brandLink =
			document.querySelector(
				".arcn-brand__name a"
			);


		if (
			brandLink instanceof
			HTMLAnchorElement
		) {
			return brandLink.href;
		}


		return "/";
	}


	/* =====================================================
	   SEARCH ICON
	   ===================================================== */

	function createSearchIcon() {

		const icon =
			document.createElement(
				"span"
			);


		icon.className =
			"arcn-search-icon";


		icon.setAttribute(
			"aria-hidden",
			"true"
		);


		return icon;
	}


	/* =====================================================
	   SEARCH FORM
	   ===================================================== */

	function createSearchForm(
		formClass,
		inputClass,
		submitClass
	) {

		const form =
			document.createElement(
				"form"
			);


		form.className =
			formClass;


		form.method =
			"get";


		form.action =
			getHomeUrl();


		form.setAttribute(
			"role",
			"search"
		);


		/* -------------------------------------------------
		   INPUT
		   ------------------------------------------------- */

		const input =
			document.createElement(
				"input"
			);


		input.className =
			inputClass;


		input.type =
			"search";


		input.name =
			"s";


		input.placeholder =
			"Search the ARCN website";


		input.autocomplete =
			"off";


		input.setAttribute(
			"aria-label",
			"Search the ARCN website"
		);


		/* -------------------------------------------------
		   SUBMIT
		   ------------------------------------------------- */

		const submit =
			document.createElement(
				"button"
			);


		submit.className =
			submitClass;


		submit.type =
			"submit";


		submit.textContent =
			"Search";


		/* -------------------------------------------------
		   BUILD
		   ------------------------------------------------- */

		form.appendChild(
			input
		);


		form.appendChild(
			submit
		);


		return {
			form: form,
			input: input
		};
	}


	/* =====================================================
	   DESKTOP SEARCH
	   ===================================================== */

	function buildDesktopSearch() {

		const headerInner =
			document.querySelector(
				HEADER_INNER_SELECTOR
			);


		const navigation =
			document.querySelector(
				NAV_SELECTOR
			);


		if (
			!headerInner ||
			!navigation
		) {
			return false;
		}


		if (
			headerInner.querySelector(
				".arcn-header-search"
			)
		) {
			return true;
		}


		/* -------------------------------------------------
		   WRAPPER
		   ------------------------------------------------- */

		const wrapper =
			document.createElement(
				"div"
			);


		wrapper.className =
			"arcn-header-search";


		/* -------------------------------------------------
		   TOGGLE
		   ------------------------------------------------- */

		const toggle =
			document.createElement(
				"button"
			);


		toggle.type =
			"button";


		toggle.className =
			"arcn-header-search__toggle";


		toggle.setAttribute(
			"aria-label",
			"Search"
		);


		toggle.setAttribute(
			"aria-expanded",
			"false"
		);


		toggle.appendChild(
			createSearchIcon()
		);


		/* -------------------------------------------------
		   PANEL
		   ------------------------------------------------- */

		const panel =
			document.createElement(
				"div"
			);


		panel.className =
			"arcn-header-search__panel";


		panel.id =
			"arcn-header-search-panel";


		panel.setAttribute(
			"aria-hidden",
			"true"
		);


		toggle.setAttribute(
			"aria-controls",
			panel.id
		);


		const search =
			createSearchForm(
				"arcn-header-search__form",
				"arcn-header-search__input",
				"arcn-header-search__submit"
			);


		panel.appendChild(
			search.form
		);


		wrapper.appendChild(
			toggle
		);


		wrapper.appendChild(
			panel
		);


		/*
		 * Insert immediately after
		 * the native WordPress navigation.
		 */
		navigation.insertAdjacentElement(
			"afterend",
			wrapper
		);


		/* -------------------------------------------------
		   OPEN
		   ------------------------------------------------- */

		function openSearch() {

			wrapper.classList.add(
				"is-open"
			);


			toggle.setAttribute(
				"aria-expanded",
				"true"
			);


			panel.setAttribute(
				"aria-hidden",
				"false"
			);


			window.setTimeout(
				function () {

					search.input.focus();

				},
				0
			);
		}


		/* -------------------------------------------------
		   CLOSE
		   ------------------------------------------------- */

		function closeSearch() {

			wrapper.classList.remove(
				"is-open"
			);


			toggle.setAttribute(
				"aria-expanded",
				"false"
			);


			panel.setAttribute(
				"aria-hidden",
				"true"
			);
		}


		/* -------------------------------------------------
		   TOGGLE
		   ------------------------------------------------- */

		toggle.addEventListener(
			"click",
			function () {

				if (
					wrapper.classList.contains(
						"is-open"
					)
				) {

					closeSearch();

				} else {

					openSearch();
				}
			}
		);


		/* -------------------------------------------------
		   CLICK OUTSIDE
		   ------------------------------------------------- */

		document.addEventListener(
			"click",
			function (event) {

				if (
					!wrapper.classList.contains(
						"is-open"
					)
				) {
					return;
				}


				if (
					event.target instanceof Node &&
					!wrapper.contains(
						event.target
					)
				) {

					closeSearch();
				}
			}
		);


		/* -------------------------------------------------
		   ESCAPE
		   ------------------------------------------------- */

		document.addEventListener(
			"keydown",
			function (event) {

				if (
					event.key ===
						"Escape" &&
					wrapper.classList.contains(
						"is-open"
					)
				) {

					closeSearch();

					toggle.focus();
				}
			}
		);


		return true;
	}


	/* =====================================================
	   MOBILE SEARCH MENU ITEM
	   ===================================================== */

	function buildMobileSearch() {

		const menu =
			document.querySelector(
				MOBILE_MENU_SELECTOR
			);


		if (!menu) {
			return false;
		}


		if (
			menu.querySelector(
				".arcn-mobile-search-item"
			)
		) {
			return true;
		}


		/* -------------------------------------------------
		   ITEM
		   ------------------------------------------------- */

		const item =
			document.createElement(
				"li"
			);


		item.className =
			"arcn-mobile-search-item";


		/* -------------------------------------------------
		   TOGGLE
		   ------------------------------------------------- */

		const toggle =
			document.createElement(
				"button"
			);


		toggle.type =
			"button";


		toggle.className =
			"arcn-mobile-search-toggle";


		toggle.setAttribute(
			"aria-expanded",
			"false"
		);


		toggle.appendChild(
			createSearchIcon()
		);


		const label =
			document.createElement(
				"span"
			);


		label.textContent =
			"Search";


		toggle.appendChild(
			label
		);


		/* -------------------------------------------------
		   FORM
		   ------------------------------------------------- */

		const search =
			createSearchForm(
				"arcn-mobile-search-form",
				"arcn-mobile-search-input",
				"arcn-mobile-search-submit"
			);


		/* -------------------------------------------------
		   BUILD
		   ------------------------------------------------- */

		item.appendChild(
			toggle
		);


		item.appendChild(
			search.form
		);


		menu.appendChild(
			item
		);


		/* -------------------------------------------------
		   TOGGLE FORM
		   ------------------------------------------------- */

		toggle.addEventListener(
			"click",
			function () {

				const open =
					item.classList.contains(
						"is-open"
					);


				if (open) {

					item.classList.remove(
						"is-open"
					);


					toggle.setAttribute(
						"aria-expanded",
						"false"
					);

				} else {

					item.classList.add(
						"is-open"
					);


					toggle.setAttribute(
						"aria-expanded",
						"true"
					);


					window.setTimeout(
						function () {

							search.input.focus();

						},
						0
					);
				}
			}
		);


		return true;
	}


	/* =====================================================
	   INITIALISE
	   ===================================================== */

	function initialiseSearch() {

		const desktopReady =
			buildDesktopSearch();


		const mobileReady =
			buildMobileSearch();


		return (
			desktopReady &&
			mobileReady
		);
	}


	/* =====================================================
	   START
	   ===================================================== */

	function start() {

		if (
			initialiseSearch()
		) {
			return;
		}


		/*
		 * header.js creates the custom mobile menu
		 * dynamically, so wait for it if necessary.
		 */
		const observer =
			new MutationObserver(
				function () {

					if (
						initialiseSearch()
					) {

						observer.disconnect();
					}
				}
			);


		observer.observe(
			document.documentElement,
			{
				childList: true,
				subtree: true
			}
		);


		window.setTimeout(
			function () {

				observer.disconnect();

			},
			5000
		);
	}


	if (
		document.readyState ===
		"loading"
	) {

		document.addEventListener(
			"DOMContentLoaded",
			start,
			{
				once: true
			}
		);

	} else {

		start();
	}

})();