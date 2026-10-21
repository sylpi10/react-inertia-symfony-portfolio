export default function JobIcon() {
    return (
        <svg
            className={"job-icon"}
            width="16px"
            height="16px"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            // décorative : le texte à côté suffit aux lecteurs d'écran
            aria-hidden="true"
        >
            <rect x="3" y="7" width="18" height="13" rx="2" />
            <path d="M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
            <path d="M3 13h18" />
        </svg>
    );
}
