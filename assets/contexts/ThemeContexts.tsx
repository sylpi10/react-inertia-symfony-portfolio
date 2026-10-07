import { createContext, ReactNode, useEffect, useState } from "react";
import { ThemeId } from "../lib/themes";

type ThemeContextValue = {
    theme: ThemeId;
    setTheme: (theme: ThemeId) => void;
};

// null : permet au hook de repérer un usage hors du provider
export const ThemeContext = createContext<ThemeContextValue | null>(null);

export function ThemeProvider({ children }: { children: ReactNode }) {
    const [theme, setTheme] = useState<ThemeId>("light");

    // les couleurs restent dans le CSS : on ne fait que poser
    // l'attribut que les blocs [data-theme="…"] ciblent
    useEffect(() => {
        document.documentElement.dataset.theme = theme;
    }, [theme]);

    return (
        <ThemeContext value={{ theme, setTheme }}>{children}</ThemeContext>
    );
}
