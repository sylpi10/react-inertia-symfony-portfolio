import { useEffect, useRef, useState } from "react";
import useMediaQuery from "../../../hooks/useMediaQuery";
import { Audience } from "../../../types/audience";
import { ReviewProps } from "../../../types/reviews";
import NavArrow from "../../ui/NavArrow";
import ReviewCard from "./ReviewCard";
import { Link } from "@inertiajs/react";

type ReviewsProps = {
    reviews: ReviewProps[];
    audience: Audience;
};

// slider des avis validés : 2 par slide, 1 sur mobile. Défilement natif
// (scroll-snap) : glisser au doigt, molette et clavier marchent sans JS,
// les flèches et les points ne font que déplacer ce défilement
export default function Reviews({ reviews, audience }: ReviewsProps) {
    const trackRef = useRef<HTMLUListElement>(null);
    const perSlide = useMediaQuery("(min-width: 769px)") ? 2 : 1;
    const slideCount = Math.ceil(reviews.length / perSlide);
    const [slide, setSlide] = useState(0);

    // largeur d'un slide : celle de la piste plus l'espace entre deux cartes
    const slideWidth = (track: HTMLUListElement) =>
        track.clientWidth +
        parseFloat(getComputedStyle(track).columnGap || "0");

    const goTo = (index: number) => {
        const track = trackRef.current;
        if (!track) return;
        track.scrollTo({ left: index * slideWidth(track) });
    };

    // slide courant déduit du défilement, qu'il vienne des boutons ou du doigt
    const onScroll = () => {
        const track = trackRef.current;
        if (!track) return;
        // dernier slide incomplet (3 avis : 2 + 1) : la piste s'arrête avant
        // d'avoir défilé d'un slide entier, on se fie donc à la butée
        const atEnd =
            track.scrollLeft >= track.scrollWidth - track.clientWidth - 2;
        setSlide(
            atEnd
                ? slideCount - 1
                : Math.round(track.scrollLeft / slideWidth(track)),
        );
    };

    // passage mobile/desktop : le nombre de slides change, on repart du début
    useEffect(() => {
        trackRef.current?.scrollTo({ left: 0, behavior: "instant" });
        setSlide(0);
    }, [perSlide]);

    if (reviews.length === 0) return null;

    return (
        <div className="section-container reviews-container">
            <div className="reviews-wrapper">
                <div className="title">
                    <h2 className={"section-title"}>avis de collaborateurs</h2>
                    <span className="reviews-count">{reviews.length} avis</span>
                </div>

                <div
                    className="reviews-slider"
                    role="group"
                    aria-roledescription="carrousel"
                    aria-label="Avis de collaborateurs"
                >
                    <ul
                        className="reviews-track"
                        ref={trackRef}
                        onScroll={onScroll}
                        // défilable au clavier (flèches gauche/droite)
                        tabIndex={0}
                    >
                        {reviews.map((review) => (
                            <ReviewCard audience={audience} review={review} />
                        ))}
                    </ul>

                    {slideCount > 1 && (
                        <div className="reviews-controls">
                            <button
                                type="button"
                                className="reviews-arrow"
                                onClick={() => goTo(slide - 1)}
                                disabled={slide === 0}
                                aria-label="Avis précédents"
                            >
                                <NavArrow direction="previous" />
                            </button>
                            <div className="reviews-dots">
                                {Array.from({ length: slideCount }, (_, i) => (
                                    <button
                                        type="button"
                                        key={i}
                                        className={`reviews-dot ${i === slide ? "active" : ""}`}
                                        onClick={() => goTo(i)}
                                        aria-label={`Avis, page ${i + 1} sur ${slideCount}`}
                                        aria-current={
                                            i === slide ? "true" : undefined
                                        }
                                    />
                                ))}
                            </div>
                            <button
                                type="button"
                                className="reviews-arrow"
                                onClick={() => goTo(slide + 1)}
                                disabled={slide >= slideCount - 1}
                                aria-label="Avis suivants"
                            >
                                <NavArrow direction="next" />
                            </button>
                        </div>
                    )}

                    <p className="share-review-link">
                        Vous avez travaillé avec moi ? :{" "}
                        <Link href={"/avis"}> Laissez un avis </Link>
                    </p>
                </div>
            </div>
        </div>
    );
}
