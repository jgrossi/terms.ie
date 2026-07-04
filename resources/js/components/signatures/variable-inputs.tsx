import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Props {
    vars: string[];
    values: Record<string, string>;
    errors: Record<string, string>;
    onChange: (name: string, value: string) => void;
}

/** Renders a text input per user variable; error keys are `variables.{VAR}`. */
export function VariableInputs({ vars, values, errors, onChange }: Props) {
    return (
        <>
            {vars.map((name) => {
                const error = errors[`variables.${name}`];
                return (
                    <div key={name} className="grid gap-2">
                        <Label htmlFor={`var-${name}`} className="font-mono text-xs">
                            {`{{${name}}}`}
                        </Label>
                        <Input
                            id={`var-${name}`}
                            value={values[name] ?? ''}
                            onChange={(e) => onChange(name, e.target.value)}
                            placeholder={`Value for {{${name}}}`}
                            aria-invalid={Boolean(error)}
                        />
                        {error && <p className="text-sm text-destructive">{error}</p>}
                    </div>
                );
            })}
        </>
    );
}
