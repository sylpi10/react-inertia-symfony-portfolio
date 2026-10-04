import profilPic from "../../static/images/avatar.webp";
import shape from "../../static/images/shape.webp";
import cv from "../../static/documents/CV_Sylvain_Pillet_fullstack_2026.pdf";
import { useEffect, useState } from "react";

export default function Hero() {
    const text: string = "Développeur\nFrontend / Fullstack";
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
            <main className="homepage">
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
                                <p className="description">
                                    Développeur web freelance à Toulouse, je
                                    conçois des sites et applications sur mesure
                                    pour les indépendants et les petites
                                    entreprises : sites vitrines avec
                                    back-office, refontes techniques. <br />5
                                    ans d’expérience en e-commerce.
                                </p>
                                <a href="#contact" className="hero-cta">
                                    Discutons de votre projet
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
                                <p className="description details">
                                    {" "}
                                    Passionné par le web et toujours curieux
                                    d’apprendre, je propose aujourd’hui mes
                                    services en freelance avec une affinité
                                    particulière pour le développement frontend,
                                    l’UI/UX et la création d’interfaces
                                    modernes, tout en gardant la possibilité d’
                                    intervenir sur les problématiques backend et
                                    l’architecture d’applications web.
                                </p>
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
                        <div className="cv-link link-button">
                            <a
                                href={cv}
                                title="Télécharger Mon CV en pdf"
                                download
                            >
                                <svg
                                    width="36px"
                                    height="36px"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M12 12V19M12 19L9.75 16.6667M12 19L14.25 16.6667M6.6 17.8333C4.61178 17.8333 3 16.1917 3 14.1667C3 12.498 4.09438 11.0897 5.59198 10.6457C5.65562 10.6268 5.7 10.5675 5.7 10.5C5.7 7.46243 8.11766 5 11.1 5C14.0823 5 16.5 7.46243 16.5 10.5C16.5 10.5582 16.5536 10.6014 16.6094 10.5887C16.8638 10.5306 17.1284 10.5 17.4 10.5C19.3882 10.5 21 12.1416 21 14.1667C21 16.1917 19.3882 17.8333 17.4 17.8333"
                                        stroke="#E4D00A"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                    />
                                </svg>
                                CV
                            </a>
                        </div>
                        <button className="project-link link-button">
                            <a href={"#projects"} title={"Voir les projets"}>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    strokeWidth="1.5"
                                    stroke="currentColor"
                                    className="size-6"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        d="M17.25 6.75 22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3-4.5 16.5"
                                    />
                                </svg>
                                Projets
                                {/*<svg viewBox="0 0 70 36">*/}
                                {/*    <path d="M6.9739 30.8153H63.0244C65.5269 30.8152 75.5358 -3.68471 35.4998 2.81531C-16.1598 11.2025 0.894099 33.9766 26.9922 34.3153C104.062 35.3153 54.5169 -6.68469 23.489 9.31527" />*/}
                                {/*</svg>*/}
                            </a>
                        </button>
                    </div>

                    <img
                        className="shape"
                        src={shape}
                        alt=""
                        width="735"
                        height="669"
                    />
                </div>
            </main>

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
