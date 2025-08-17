import AppLayout from "@/layouts/app-layout";
import { Head } from "@inertiajs/react";

export default function Dashboard() {
    return (
        <div>
            <Head title="Dashboard" />
            Dashboard
        </div>
    );
}

Dashboard.layout = (page) => <AppLayout children={page} />;
