import {
    Card,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Head } from "@inertiajs/react";

export default function AuthLayout({ title = "", description = "", children }) {
    return (
        <div className="h-screen p-4 flex items-center justify-center">
            <Head title={title} />
            <Card className="w-full max-w-md">
                <CardHeader>
                    <CardTitle>{title}</CardTitle>
                    <CardDescription>{description}</CardDescription>
                </CardHeader>
                {children}
            </Card>
        </div>
    );
}
