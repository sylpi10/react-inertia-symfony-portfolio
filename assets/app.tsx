import { createInertiaApp, router } from "@inertiajs/react";
import { resolvePage } from "./lib/resolvePage";
import Layout from "./layouts/Layout";

createInertiaApp({
    resolve: resolvePage,
    layout: () => Layout,
});

// Le <title> est rendu par Twig au premier chargement ; on le met à jour
// lors des navigations Inertia, qui ne rechargent pas le <head>
let previousPath = window.location.pathname;
router.on("navigate", (event) => {
    const seo = event.detail.page.props.seo as { title?: string } | undefined;
    if (seo?.title) {
        document.title = seo.title;
    }

    // nouvelle page (pas le premier chargement, ni une ancre de la même page) :
    // focus sur le h1, le lecteur d'écran annonce la page au lieu de rester
    // sur le lien cliqué, qui n'existe plus
    const path = new URL(event.detail.page.url, window.location.origin).pathname;
    if (path !== previousPath) {
        requestAnimationFrame(() => {
            const title = document.querySelector<HTMLElement>("main h1");
            title?.setAttribute("tabindex", "-1");
            title?.focus({ preventScroll: true });
        });
    }
    previousPath = path;
});
