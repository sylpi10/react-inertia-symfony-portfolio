// rendue par InertiaErrorListener, avec le code HTTP de l'erreur
export default function Error({ status }: { status: number }) {
    return (
        <div>
            <h1>Erreur {status}</h1>
            <p>
                {status === 404
                    ? "Cette page n'existe point !"
                    : "Une erreur est survenue, réessayez un peu plus tard."}
            </p>
        </div>
    );
}
