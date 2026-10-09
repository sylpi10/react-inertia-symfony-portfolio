import profilPic from "../../../static/images/avatar.webp";
import shape from "../../../static/images/shape.webp";
import { ReactNode, useEffect, useState } from "react";
import { Audience } from "../../../types/audience";
import { AudienceSwitch } from "./AudienceSwitch";
import ArrowUpIcon from "../../ui/ArrowUpIcon";
import useMouseParallax from "../../../hooks/useMouseParallax";
import NavArrow from "../../ui/NavArrow";

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
        // « réduire les animations » : titre complet tout de suite
        if (matchMedia("(prefers-reduced-motion: reduce)").matches) {
            setIndex(text.length);
            return;
        }
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
    const heroRef = useMouseParallax<HTMLDivElement>();

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
                <div className="hero-area" ref={heroRef}>
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
                                            {/* retiré une fois le texte tapé : pas de clignotement sans fin */}
                                            {i === cursorLine &&
                                                index < text.length && (
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
                                <div className="description">{intro}</div>
                                <div className="description details">
                                    {details}
                                </div>
                                <a href="#contact" className="hero-cta">
                                    {cta}
                                    <NavArrow direction={"next"} />
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
                                <p className="name-tag">Sylvain Pillet</p>
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
                <a
                    href={"#home"}
                    className="back-to-top"
                    aria-label="Retour en haut de page"
                >
                    <ArrowUpIcon />
                </a>
            )}
        </>
    );
}
