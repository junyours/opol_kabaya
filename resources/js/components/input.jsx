import { Input as Inpt } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Eye, EyeOff } from "lucide-react";

export default function Input({ label = "", error, type = "text", ...props }) {
    const [showPassword, setShowPassword] = useState(false);

    return (
        <div className="space-y-0.5">
            <Label className={cn(error && "text-destructive")}>{label}</Label>
            <div className="relative">
                <Inpt
                    type={
                        type === "password"
                            ? showPassword
                                ? "text"
                                : "password"
                            : type
                    }
                    className={cn(
                        error && "border-destructive",
                        type === "password" && "pr-12"
                    )}
                    {...props}
                />
                {type === "password" && (
                    <div className="absolute inset-y-0 right-0 justify-center">
                        <Button
                            onClick={() => setShowPassword(!showPassword)}
                            variant="ghost"
                            size="icon"
                            type="button"
                            className="text-muted-foreground"
                        >
                            {showPassword ? <Eye /> : <EyeOff />}
                        </Button>
                    </div>
                )}
            </div>
            {error && <p className="text-sm text-destructive">{error}</p>}
        </div>
    );
}
