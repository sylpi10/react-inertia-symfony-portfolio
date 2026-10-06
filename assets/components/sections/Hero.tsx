import profilPic from "../../static/images/avatar.webp";
import shape from "../../static/images/shape.webp";
// import cv from "../../static/documents/CV_Sylvain_Pillet_fullstack_2026.pdf";
// import { Link } from "@inertiajs/react";
import { ReactNode, useEffect, useState } from "react";
import { Audience } from "../../types/audience";
import { AudienceSwitch } from "./AudienceSwitch";

type HeroProps = {
    audience: Audience;
    // h1 tapé à la machine ; \n pour passer à la ligne
    text: string;
    intro: ReactNode;
    cta: string;
    details: ReactNode;
};

// texte propre à chaque page (accueil, page équipe) : pas de contenu dupliqué
export default function Hero({
    audience,
    text,
    intro,
    cta,
    details,
}: HeroProps) {
    const lines: string[] = text.split("\n");
    const [index, setIndex] = useState(0);

    useEffect(() => {
        if (index < text.length) {
            const timeout = setTimeout(() => setIndex(index + 1), 160);

            return () => clearTimeout(timeout);
        }
    }, [index, text]);

    // position de départ de chaque ligne dans text (+1 pour le \n)
    const lineStarts: number[] = lines.map((_, i) =>
        lines.slice(0, i).reduce((n, line) => n + line.length + 1, 0),
    );
    const cursorLine: number =
        lineStarts.filter((start) => start <= index).length - 1;

    const [hasScrolledPast, setHasScrolledPast] = useState(false);

    // scroll for sticky nav
    useEffect(() => {
        const handleScroll = () => {
            const scrolled = window.scrollY > 200;
            setHasScrolledPast(scrolled);
        };
        window.addEventListener("scroll", handleScroll);
        // Nettoyage de l'event listener
        return () => window.removeEventListener("scroll", handleScroll);
    }, []);

    return (
        <>
            <div className="homepage">
                <div className="hero-area">
                    <div className="presentation">
                        <div className="person">
                            {/* texte complet dans le HTML dès le rendu serveur (Google) ;
                                la partie pas encore tapée est invisible mais occupe déjà sa
                                place : le titre ne change pas de taille (pas de CLS) */}
                            <h1
                                className="typewriter"
                                aria-label={text.replace("\n", " ")}
                            >
                                {lines.map((line, i) => {
                                    const typed = Math.min(
                                        Math.max(index - lineStarts[i], 0),
                                        line.length,
                                    );
                                    return (
                                        <span key={i} aria-hidden="true">
                                            {line.slice(0, typed)}
                                            {i === cursorLine && (
                                                <span className="cursor">
                                                    |
                                                </span>
                                            )}
                                            <span className="typewriter-rest">
                                                {line.slice(typed)}
                                            </span>
                                            {i < lines.length - 1 && <br />}
                                        </span>
                                    );
                                })}
                            </h1>
                            <div className="person-description">
                                <p className="description">{intro}</p>
                                <p className="description details">{details}</p>
                                <a href="#contact" className="hero-cta">
                                    {cta}
                                    <svg
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeWidth="2"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        aria-hidden="true"
                                    >
                                        <path d="M5 12h14M13 6l6 6-6 6" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div className="picture-name-wrapper">
                            <div className={`picture-name-container`}>
                                <img
                                    src={profilPic}
                                    alt="Sylvain Pillet, développeur à Toulouse"
                                    width="300"
                                    height="347"
                                    fetchPriority="high"
                                />
                                <h2>Sylvain Pillet</h2>
                            </div>
                        </div>
                    </div>

                    <div className="links-wrapper">
                        <AudienceSwitch audience={audience} />
                    </div>

                    <img
                        className="shape"
                        src={shape}
                        alt=""
                        width="735"
                        height="669"
                    />
                </div>
            </div>

            {hasScrolledPast && (
                <a href={"#home"} className="back-to-top">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        strokeWidth="2"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        className="lucide lucide-arrow-up-from-dot-icon lucide-arrow-up-from-dot"
                    >
                        <path d="m5 9 7-7 7 7" />
                        <path d="M12 16V2" />
                        <circle cx="12" cy="21" r="1" />
                    </svg>
                </a>
            )}
        </>
    );
}
