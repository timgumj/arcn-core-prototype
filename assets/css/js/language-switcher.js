(function () {
	"use strict";


	const MOBILE_MAX_WIDTH =
		980;


	function isMobile() {

		return (
			window.innerWidth <=
			MOBILE_MAX_WIDTH
		);
	}


	function getSwitcher() {

		return document.querySelector(
			".arcn-language-switcher"
		);
	}


	function getHeaderInner() {

		return document.querySelector(
			".arcn-site-header__inner"
		);
	}


	function getNavigation() {

		return document.querySelector(
			".arcn-primary-nav"
		);
	}


	function getMobileMenu() {

		return document.querySelector(
			".arcn-mobile-menu"
		);
	}


	function getMobileItem() {

		return document.querySelector(
			".arcn-mobile-language-item"
		);
	}


	function moveSwitcher() {

		const switcher =
			getSwitcher();


		if (!switcher) {
			return false;
		}


		/* =================================================
		   MOBILE / TABLET
		   ================================================= */

		if (isMobile()) {

			const mobileMenu =
				getMobileMenu();


			if (!mobileMenu) {
				return false;
			}


			let item =
				getMobileItem();


			if (!item) {

				item =
					document.createElement(
						"li"
					);


				item.className =
					"arcn-mobile-language-item";


				mobileMenu.appendChild(
					item
				);
			}


			if (
				switcher.parentElement !==
				item
			) {

				item.appendChild(
					switcher
				);
			}


			return true;
		}


		/* =================================================
		   DESKTOP
		   ================================================= */

		const headerInner =
			getHeaderInner();


		const navigation =
			getNavigation();


		if (
			!headerInner ||
			!navigation
		) {
			return false;
		}


		if (
			switcher.parentElement !==
			headerInner
		) {

			navigation.insertAdjacentElement(
				"afterend",
				switcher
			);
		}


		const mobileItem =
			getMobileItem();


		if (
			mobileItem &&
			!mobileItem.contains(
				switcher
			)
		) {

			mobileItem.remove();
		}


		return true;
	}


	function initialise() {

		if (
			moveSwitcher()
		) {
			return;
		}


		/*
		 * header.js creates the mobile menu after load.
		 * Wait until it exists.
		 */

		const observer =
			new MutationObserver(
				function () {

					if (
						moveSwitcher()
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


	function handleResize() {

		moveSwitcher();
	}


	if (
		document.readyState ===
		"loading"
	) {

		document.addEventListener(
			"DOMContentLoaded",
			initialise,
			{
				once: true
			}
		);

	} else {

		initialise();
	}


	window.addEventListener(
		"resize",
		handleResize,
		{
			passive: true
		}
	);

})();