import { Link, usePage } from "@inertiajs/react";
import logo from "../static/images/logo.webp";
import { useState, useRef, ReactNode } from "react";
import { Audience } from "../types/audience";

const CLIENT_PATH = "/creation-site-web";

// sections propres à chaque page (le choix de la page se fait dans le hero)
const navigation = {
    client: {
        links: [
            { href: `${CLIENT_PATH}#services`, label: "Services" },
            { href: `${CLIENT_PATH}#projects`, label: "Projets" },
            { href: `${CLIENT_PATH}#a-propos`, label: "À propos" },
            { href: `${CLIENT_PATH}#parcours`, label: "Parcours" },
            { href: `${CLIENT_PATH}#contact`, label: "Contact" },
        ],
    },
    team: {
        links: [
            { href: "/#competences", label: "Compétences" },
            { href: "/#projects", label: "Projets" },
            { href: "/#parcours", label: "Parcours" },
            { href: "/#contact", label: "Contact" },
        ],
    },
};

export default function Layout({ children }: { children: ReactNode }) {
    const { url, props, component } = usePage<{ audience?: Audience }>();
    // page projet : navigation de son mode ; page d'erreur : celle de l'accueil
    const { links } = navigation[props.audience ?? "team"];
    // accueil (Team) ou page création de site (Client)
    const isHomePage = component === "Team" || component === "Client";
    const [isMobileOpen, setIsMobileOpen] = useState(false);
    const headerRef = useRef(null);

    // Ferme le menu mobile quand un lien est cliqué
    const handleLinkClick = () => {
        setIsMobileOpen(false);
    };

    return (
        <>
            <header className="header" ref={headerRef}>
                <nav
                    className={`navbar ${isHomePage ? "default-menu-class" : ""} ${isMobileOpen ? "mobile-nav" : ""}`}
                >
                    <span className="brand">
                        <Link href="/" onClick={handleLinkClick}>
                            <img
                                src={logo}
                                className="logo"
                                alt="Logo Sylvain Pillet"
                                width="39"
                                height="60"
                            />
                        </Link>
                    </span>
                    <ul className="navlist">
                        {links.map((link) => (
                            <li key={link.href}>
                                <Link
                                    href={link.href}
                                    onClick={handleLinkClick}
                                    className={
                                        url === link.href ? "active" : undefined
                                    }
                                >
                                    {link.label}
                                    {/* étiré en largeur seulement (nav.scss) */}
                                    <svg
                                        viewBox="0 0 70 36"
                                        preserveAspectRatio="none"
                                    >
                                        <path d="M6.9739 30.8153H63.0244C65.5269 30.8152 75.5358 -3.68471 35.4998 2.81531C-16.1598 11.2025 0.894099 33.9766 26.9922 34.3153C104.062 35.3153 54.5169 -6.68469 23.489 9.31527" />
                                    </svg>
                                </Link>
                            </li>
                        ))}
                    </ul>

                    <span
                        className={`burger ${isMobileOpen ? "open" : ""}`}
                        onClick={() => setIsMobileOpen((prev) => !prev)}
                    >
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </nav>
            </header>

            <main>{children}</main>
        </>
    );
}
