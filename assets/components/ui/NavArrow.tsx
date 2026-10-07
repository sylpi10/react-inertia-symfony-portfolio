// flèche des liens de navigation (précédent / suivant, page d'erreur)
export default function NavArrow({
    direction,
}: {
    direction: "previous" | "next";
}) {
    return (
        <svg
            className={`nav-arrow ${direction}`}
            width="28"
            height="28"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            aria-hidden="true"
        >
            {direction === "previous" ? (
                <path d="M19 12H5M11 18l-6-6 6-6" />
            ) : (
                <path d="M5 12h14M13 6l6 6-6 6" />
            )}
        </svg>
    );
}
