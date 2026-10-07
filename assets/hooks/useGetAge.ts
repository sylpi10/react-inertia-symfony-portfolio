export default function useGetAge(birthDate: string) {
    const today: Date = new Date();
    const birthDateObj: Date = new Date(birthDate);
    let age: number = today.getFullYear() - birthDateObj.getFullYear();
    const month: number = today.getMonth();
    const day: number = today.getDate();

    // Adjust age if birthday hasn't occurred yet this year
    if (
        month < birthDateObj.getMonth() ||
        (month === birthDateObj.getMonth() && day < birthDateObj.getDate())
    ) {
        age--;
    }

    return age;
}
