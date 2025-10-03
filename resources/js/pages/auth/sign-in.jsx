import AuthLayout from "@/layouts/auth-layout";
import { CardContent, CardFooter } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import Input from "@/components/input";
import { useForm } from "@inertiajs/react";
import { Loader2 } from "lucide-react";

export default function SignIn({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, clearErrors } = useForm({
        email: "",
        password: "",
        remember: false,
    });

    const handleLogin = (e) => {
        e.preventDefault();
        clearErrors();
        post("/login");
    };

    return (
        <form onSubmit={handleLogin}>
            <CardContent className="space-y-4">
                <Input
                    label="Email Address"
                    type="email"
                    value={data.email}
                    onChange={(e) => setData("email", e.target.value)}
                    error={errors.email}
                />
                <Input
                    label="Password"
                    type="password"
                    value={data.password}
                    onChange={(e) => setData("password", e.target.value)}
                    error={errors.password}
                />
            </CardContent>
            <CardFooter>
                <Button className="w-full" disabled={processing}>
                    {processing ? (
                        <Loader2 className="animate-spin" />
                    ) : (
                        "Login"
                    )}
                </Button>
            </CardFooter>
        </form>
    );
}

SignIn.layout = (page) => (
    <AuthLayout
        title="Sign In"
        description="Please login your account."
        children={page}
    />
);
