import { useContext } from "react";
import { ThemeContext } from "../contexts/ThemeContexts";

export default function useTheme() {
    const context = useContext(ThemeContext);
    if (!context) {
        throw new Error("useTheme doit être appelé sous un <ThemeProvider>");
    }
    return context;
}
