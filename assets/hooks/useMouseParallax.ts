import { useEffect, useRef } from "react";

// parallaxe à la souris : écrit --mx / --my (de -1 à 1, lissés) sur l'élément ;
// chaque calque se décale en CSS selon sa profondeur (parallax.scss).
// Rien sur écran tactile ni avec « réduire les animations ».
export default function useMouseParallax<T extends HTMLElement>() {
    const ref = useRef<T>(null);

    useEffect(() => {
        const el = ref.current;
        if (
            !el ||
            !matchMedia("(hover: hover) and (pointer: fine)").matches ||
            matchMedia("(prefers-reduced-motion: reduce)").matches
        ) {
            return;
        }

        const target = { x: 0, y: 0 };
        const current = { x: 0, y: 0 };
        let frame = 0;

        // rapproche la position courante de la cible à chaque image (8 %),
        // et s'arrête une fois arrivé : pas de boucle qui tourne pour rien
        const tick = () => {
            current.x += (target.x - current.x) * 0.08;
            current.y += (target.y - current.y) * 0.08;
            el.style.setProperty("--mx", current.x.toFixed(4));
            el.style.setProperty("--my", current.y.toFixed(4));
            const settled =
                Math.abs(target.x - current.x) < 0.001 &&
                Math.abs(target.y - current.y) < 0.001;
            frame = settled ? 0 : requestAnimationFrame(tick);
        };

        const move = (x: number, y: number) => {
            target.x = x;
            target.y = y;
            if (!frame) frame = requestAnimationFrame(tick);
        };

        // position de la souris ramenée entre -1 et 1, centre de l'écran à 0
        const onMove = (e: PointerEvent) =>
            move(
                (e.clientX / innerWidth) * 2 - 1,
                (e.clientY / innerHeight) * 2 - 1,
            );
        // souris sortie de la fenêtre : retour au centre
        const onLeave = () => move(0, 0);

        addEventListener("pointermove", onMove);
        document.documentElement.addEventListener("pointerleave", onLeave);
        return () => {
            removeEventListener("pointermove", onMove);
            document.documentElement.removeEventListener("pointerleave", onLeave);
            cancelAnimationFrame(frame);
        };
    }, []);

    return ref;
}
