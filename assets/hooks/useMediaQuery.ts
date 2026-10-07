import { useEffect, useState } from "react";

// false au rendu serveur, puis la vraie valeur dans le navigateur
export default function useMediaQuery(query: string) {
    const [matches, setMatches] = useState(false);

    useEffect(() => {
        const mediaQuery = window.matchMedia(query);
        setMatches(mediaQuery.matches);

        // suit les changements (rotation, redimensionnement)
        const onChange = (event: MediaQueryListEvent) =>
            setMatches(event.matches);
        mediaQuery.addEventListener("change", onChange);
        return () => mediaQuery.removeEventListener("change", onChange);
    }, [query]);

    return matches;
}
