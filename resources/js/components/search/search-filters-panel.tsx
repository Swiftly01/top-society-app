import { router } from '@inertiajs/react';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import type { SearchFilters } from '@/types/search';

interface SearchFiltersPanelProps {
    filters: SearchFilters;
    query: string;
}

export function SearchFiltersPanel({ filters, query }: SearchFiltersPanelProps) {
    function updateQuery(params: Record<string, string>) {
        router.get(
            '/search',
            { q: query, ...params },
            { preserveScroll: true, preserveState: true, only: ['results', 'filters', 'totalResults', 'resultsStart', 'resultsEnd'] },
        );
    }

    return (
        <div className="flex flex-col gap-6">
            <h2 className="font-serif text-xl font-bold">Filters</h2>

            <div>
                <p className="mb-2 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                    Date Range
                </p>
                <div className="flex flex-col gap-2">
                    {filters.dateRange.map((option) => (
                        <label key={option.value} className="flex items-center gap-2 text-sm">
                            <input
                                type="radio"
                                name="dateRange"
                                value={option.value}
                                checked={filters.activeDateRange === option.value}
                                onChange={() => updateQuery({ dateRange: option.value })}
                                className="accent-red-600"
                            />
                            {option.label}
                        </label>
                    ))}
                </div>
            </div>

            <div>
                <p className="mb-2 text-xs font-semibold tracking-widest text-muted-foreground uppercase">Sort By</p>
                <Select value={filters.activeSort} onValueChange={(value) => updateQuery({ sort: value })}>
                    <SelectTrigger className="w-full">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        {filters.sortOptions.map((option) => (
                            <SelectItem key={option.value} value={option.value}>
                                {option.label}
                            </SelectItem>
                        ))}
                    </SelectContent>
                </Select>
            </div>

            <div>
                <p className="mb-2 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                    Content Type
                </p>
                <div className="flex flex-col gap-2.5">
                    {filters.contentTypes.map((type) => (
                        <label key={type.value} className="flex items-center gap-2 text-sm">
                            <Checkbox
                                checked={type.checked}
                                onCheckedChange={(checked) =>
                                    updateQuery({ [`type_${type.value}`]: checked ? '1' : '0' })
                                }
                            />
                            {type.label}
                        </label>
                    ))}
                </div>
            </div>
        </div>
    );
}
