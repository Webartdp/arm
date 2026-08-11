@php
    $countryStats = [
        'ru' => [
            'title' => 'Посетители сайта по странам',
            'items' => [
                ['flag' => 'am', 'name' => 'Армения', 'value' => '62.07%'],
                ['flag' => 'ru', 'name' => 'Россия', 'value' => '17.63%'],
                ['flag' => 'us', 'name' => 'Соединённые Штаты Америки', 'value' => '2.64%'],
                ['flag' => 'fr', 'name' => 'Франция', 'value' => '2.38%'],
                ['flag' => 'de', 'name' => 'Германия', 'value' => '2.13%'],
                ['flag' => 'nl', 'name' => 'Нидерланды', 'value' => '1.22%'],
                ['flag' => 'es', 'name' => 'Испания', 'value' => '0.98%'],
                ['flag' => 'in', 'name' => 'Индия', 'value' => '0.73%'],
                ['flag' => 'be', 'name' => 'Бельгия', 'value' => '0.66%'],
                ['flag' => 'id', 'name' => 'Индонезия', 'value' => '0.61%'],
                ['flag' => 'ge', 'name' => 'Грузия', 'value' => '0.52%'],
                ['flag' => 'gb', 'name' => 'Великобритания', 'value' => '0.51%'],
                ['flag' => 'cn', 'name' => 'Китай', 'value' => '0.51%'],
                ['flag' => null, 'name' => 'Другое', 'value' => '7.41%'],
            ],
        ],
        'en' => [
            'title' => 'Website visitors by country',
            'items' => [
                ['flag' => 'am', 'name' => 'Armenia', 'value' => '62.07%'],
                ['flag' => 'ru', 'name' => 'Russia', 'value' => '17.63%'],
                ['flag' => 'us', 'name' => 'United States of America', 'value' => '2.64%'],
                ['flag' => 'fr', 'name' => 'France', 'value' => '2.38%'],
                ['flag' => 'de', 'name' => 'Germany', 'value' => '2.13%'],
                ['flag' => 'nl', 'name' => 'Netherlands', 'value' => '1.22%'],
                ['flag' => 'es', 'name' => 'Spain', 'value' => '0.98%'],
                ['flag' => 'in', 'name' => 'India', 'value' => '0.73%'],
                ['flag' => 'be', 'name' => 'Belgium', 'value' => '0.66%'],
                ['flag' => 'id', 'name' => 'Indonesia', 'value' => '0.61%'],
                ['flag' => 'ge', 'name' => 'Georgia', 'value' => '0.52%'],
                ['flag' => 'gb', 'name' => 'United Kingdom', 'value' => '0.51%'],
                ['flag' => 'cn', 'name' => 'China', 'value' => '0.51%'],
                ['flag' => null, 'name' => 'Other', 'value' => '7.41%'],
            ],
        ],
        'am' => [
            'title' => 'Կայքի այցելուներն ըստ երկրների',
            'items' => [
                ['flag' => 'am', 'name' => 'Հայաստան', 'value' => '62.07%'],
                ['flag' => 'ru', 'name' => 'Ռուսաստան', 'value' => '17.63%'],
                ['flag' => 'us', 'name' => 'Ամերիկայի Միացյալ Նահանգներ', 'value' => '2.64%'],
                ['flag' => 'fr', 'name' => 'Ֆրանսիա', 'value' => '2.38%'],
                ['flag' => 'de', 'name' => 'Գերմանիա', 'value' => '2.13%'],
                ['flag' => 'nl', 'name' => 'Նիդերլանդներ', 'value' => '1.22%'],
                ['flag' => 'es', 'name' => 'Իսպանիա', 'value' => '0.98%'],
                ['flag' => 'in', 'name' => 'Հնդկաստան', 'value' => '0.73%'],
                ['flag' => 'be', 'name' => 'Բելգիա', 'value' => '0.66%'],
                ['flag' => 'id', 'name' => 'Ինդոնեզիա', 'value' => '0.61%'],
                ['flag' => 'ge', 'name' => 'Վրաստան', 'value' => '0.52%'],
                ['flag' => 'gb', 'name' => 'Մեծ Բրիտանիա', 'value' => '0.51%'],
                ['flag' => 'cn', 'name' => 'Չինաստան', 'value' => '0.51%'],
                ['flag' => null, 'name' => 'Այլ', 'value' => '7.41%'],
            ],
        ],
    ][$locale] ?? null;

    $countryFlagData = [
        'am' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAAs0lEQVRoge3asQnCUBhF4fsklVhY2gQU3EMygLtkD3tHcA5HcAVbUXADQzojp88DzzfB5cArfnjlke01+tY3SQ4mmVgvKhpTDaMAowCjAKMAowCjAKMAowCjAKMAowCjAKOA5pTjvbpVM2rzWpY2l8/fFmCdzwcYBRgFGAUYBRgFGAUYBRgFGAUYBRjlx27zXJX3ee9BOOVBSIwCjAKMAowCjAKMAowCjAKMAowCjALGL6NddavmlNwGqoMM26UffqoAAAAASUVORK5CYII=',
        'ru' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAAs0lEQVRoge3XMQrCQBBG4VlZUCRdOoscwgMIHsA76SHS50aClWUqsbFbbBW7RF6fLd53gp/HNJNKKefQ1PCL8jHJzHFV0ZhqGAUYBRgFGAUYBRgFGAUYBRgFGAXk6+1Z3aglbda5SftD70M450NIjAKMAowCjAKMAowCjAKMAowCjAKMAtI9Oh/Cice2PXkpf3bvVzEKMAowCjAKMAowCjAKMAowCjAKMArIEXGpbtWSIsYvHNAU71TCLwUAAAAASUVORK5CYII=',
        'us' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAADkElEQVRoge2ZX0hTcRTHz50/1OmuG1aIpgUFNqKXpmKWMM0MJCWfKoPKQKyIAitJe8iCcD6ERDEKiwgftDf30B8UjA2shjqpF50m+H8zNFJ3mza1G+e2OzOnbrK4P/B+YGy/L3e75/x+v3O+994x5YlZmR5FWCpIgHrewxU4B/qlOPcaTJGJCOW99tg4vRRn17q+Q4FzQIpTr4VFQVtENKAIV0ZEYhza5B1Qce208C4SjFZdVbIhjTbC1KyKROfnzkFrhxDomVO50GrpWpZsoNrxY4eg6VVbUBqrPwCpPSba5oVjyiqM5ubWDv32+K2CoopWwpHsFHBxbrB29sAPbjbkGjLmnIQ03R548aRC4jlYgUVBFuaF8sEgWTYKevtHICdLB4f1Ouj7MrJMS9NpQ6LhuWhlcdqlYop355nRfXL0OrhcWgiG2gaw9w1DjCpK2O7Bag7Hn4TX0zpsdsF9Ku022qZnyX1mODeMOSbB5XILieJ4I5r4W4FotMKU7iu0tik16WJ82Fse3b8KMy43XCl/KCQQak2Exp0SpmZtPvdBzhYdhfrGFmg1dwmNERMIVmu32cHhnFxXA4rdh4ifMNibZUXCihqfmjas1dQ2BKSZ/rJv2mDKbzywvrZ8Ssdtjo1wdGxCsFB0iO7eIUhK2BZyDRs0rZa8OO2ykYmm5jmIjfPZJDbDwvxMYYyridcVodSc4998fcVlsUInkyTxNKyAW2HJxjqTb4Jw5+CWD0YD7zXPehpeEdNqyb6egivKei0WV1IYs1GCNuZtkqHUaIa5fslgfdvZ67NkDLz6dokQODZSTCjUmsj+XfHw+GIBVdPDEGIjv9Qxc6KAJYTbWlxJTCBYDS16LQ37i+g8RBMDbFaGFLmvBQd4Q7g3rZg31pl4pKa2gccxvuobW4LSbt19FtB3xePOXTDwFGIms+/eRwKE+3YI2qUIriiO/5fm/twNvdknqNom/MLC0g2hFAFQf0MoswTT/vLNczfnPi/FnKiUEZCcsIWq5RDcJ+1k3nAAx24mOLl8/EDsBws1TEQ4dYFJBboP4T7apjZn+qsil48/iPaDScP/9NAXmUQwhKiIKiNFLp/lyOXjDzJaWRMZnhA3RF9o0rA4w3nIeI0RHx3s3IwTsAqDcvn4gSQaKiI9jq9y+XgJi1F5GJ7n7wBAFRUR0YH86MAfsvv8g+w+/pHdxx/4Z5iZvrAkBGDwNyVuUPkSPFjlAAAAAElFTkSuQmCC',
        'fr' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAA2klEQVRoge3aMWpCURRF0RMVC/lFqlSCRUohhAzA1M7GMVhqY2EqG8lIMhQzhU+UKBJEEKtdvlcE9hrA4bLr+/A2+VgleU1hL+OnZr2c/pTevWrnm1O7+OzX2E6y7d2CvJde7nY7pSfv/tr9d5JRpfmvepf/Y0YBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYB15fRWZLH0sO/x3OTpMofbe95OEhyqLGdZHcBgn0Wa25QcFcAAAAASUVORK5CYII=',
        'de' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAEUklEQVRoge2Zb2gbZRzHv/l7yTXJJW3XuXUk0ypLq221sE6UVUqLiFuRDBmCjvWFOsEMRJhgnS9Ex14UQaGg0FeavhhzuNJSkdnAjPvDWvavHTRF7bq6uhrC1qTpLX8beR57l9w85pveXZD7wJHcPc/l+eV7z+/3e37PGQCchU457xFRirokEjqNFWRMxaCLIoMuigy6KDLoosigiyKDLooMZqUHaPEz4FymDfmtWDyP2bnshvzWw1BelEYb+j+sA+c04dZijh6tjQw9v/DNKm4PZzG/+ymwtdPoaGfR4reJ90YmePjqLfRIrBSwP7j4/xCFMDKewqG+O+J5s5/BmW+9uPtHAXPRLM7XZREejGFvlwMnB7bRPrsCNzEdzYj3fPRurRqmUlSJKW6XdBgyW5aTayhwwDlPGpOR85J2MitIn3K89RY1TKWYP0H1LSUHcJjNtVMzmSqsP+3d7azoEr7DFrxy2AW2MUr7JpJr9JO41my4AddnMpiKpvHB8RgWFnPwGcyZblQvKWnvPHKsuRdOn5KDXMvnMYF/3GAkvILgQQ/90wKh0wnx+y+TvPid9CGx58jxv8RrW4tmRml7AfCqpmQSI5pfmqNCEBchHBuIy/YdDa9gV2BeElfUQpVAS+jpcmA0nEL8boEG3UN9gL/BioU/pbGjbe8cor9LMwxxOTVRbaaQ1EzYXFt6Dg/++Qev1XhMdJ3TobIoqs0UgR2PWfHma26aXYZOJ8BYDchkpftcbwQ4Gli1QrNlvm89xba32sVr3q0WSZtWaF77cGVrGK3FENBclNayZX1LI6OpLQKaizJ7s5Ryr9xIa2qLgKai7N/jwqkfVsTzi1fuo+u5Ki1NomgiCsk4VXYjTo4l/9UWvrBKayWW1e55qT7yo14LDgQ4TIzxeNJkhd1qENscrBFPmxiMn1jFV58+QvtqgdnSrqwuN3Klxdiz223gIwWM2JLI/1rEzm47Dra5acFH+PzoZkxPpDE/lsXwl0nUTxrBuUrBt3kHA4vCz7GYhsPsDikb8R8fz+KnyCqanmDw8VubMDhwD2eGUliyFsBdNoJ7oVQcknooOVlAyrSG0KkEPJtMeP/tGiwa8rgeTYNvssB9VPEMlVLcfXq6nYABeOd1D747kUD2fhHP7LQj2FsNfwdDCz+B0fEUmp5nEHjVicAWBxxGI0JfL9MVrs1qxIF9nNLmUgz8rF/xd8mJZAFHjsUQfNkDljVgsP8eMh6AdxcxNJyQ9CW7b201DJau5vHFyBac+5HHz7/xCPZ6Nmyv9z/oVEUUgdD3CSzczqGn20GvfDYQh9tponux8eU89r3oojVRf18dbSczh6x4yaxSEXVFEZiaSSNyiac7bcQ1SCy5E8vD38DQIpG4G1ndUtdTH21EKUeYPQTvNotqceMhaC8K1jeyUTkFYafq+ylyVEp1LKC/NpVBF0UGXRQZdFFk0EWRgWSfzoqzSkuAa38DrFxtGN8eqCIAAAAASUVORK5CYII=',
        'nl' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAAoCAYAAACo5zetAAAACXBIWXMAAAsSAAALEgHS3X78AAAAs0lEQVRoge3ToQ3CUBhF4f81b4z62hIWwODZAlF0ZwDPCCQdgwkwrICmGKowOAQ9vk16vgluTnLT5XA6f7quCf0UphgzCjAKMAowCjAKMAowCjAKMAowCjAKyKks+7rdz27YlHJRVX29Wy+3APA+wCjAKMAowCjAKMAowCjAKMAowCggP1/D+3Z/zG7YlNJqe9xExHW5Cca8DzAKMAowCjAKMAowCjAKMAowCjAKMMq/iPgCdbIRnzj39BoAAAAASUVORK5CYII=',
        'es' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAAoCAYAAACo5zetAAAACXBIWXMAAAsSAAALEgHS3X78AAAD9klEQVRoge2aX0xbVRjAf7e0tJSOdgxKGf+ZMCRE5MEYtqgBkiUu2xRNyJbsxQfMXnzwZU9T54zRB1+WuYdli8ZkiYmwzUiUZI6J1iAqY25SNmCjMCmFlV3o/0J77zWAxpjdF0qREu/v9ebc+51fvu+c79wc4WtqTwLvoPE33+s0FY+jSVFBk6KCJkUFTYoKmhQVNCkqaFJU0OfUKTadIe3i2jQUCYu+uE15Or9J+Z8qeJzQqLBLKx8VUipFDMqcuCCm8pWbgn69HxX0OWSYn8Ab2s51t5n8KoH7QimleWGMwavIMc+Wk7KuTNGZivg1dIzLAzVcvOrnRvenPJz1cO7dkziHc/nMWcyE0pa6aP8j1pUpQmYB4+OT3Dt1gZm6HXjnE8SGush3ZPLtJ2eZmHFTXfUe5batpCTJTPGHZC5dCyEFBvnu4hlGGxTE4BTZjjxsuzNYMmcwGfYREiTs0dMrYzwLcqpj3zCSknK+O4g7INPRE+RoixX5doy5BybGfpjH5ynCGy4kHnXQfrge6zYLH34R4PPrQaQtsvMnVT4e0cz4lAFr4wLTHQnKFk34c0McEe2IFXUossTOpj5CkQZu3Ncx8scS4rxC+z4ZqyX9u4CkImxubCTLUkX7/tXFYqDETzhbot/qZy4W5UxPN5fOleLsWW2V9+9rZT6YvSWEkIyU5fXEMzxAa6WLri9j+OdkElIbel0bZSYjcjxOa30jvtA81TXVXPsmhu5eJwcqffQ4IxszixSz5vIZ+jnOj1eCGPJyMYsiDr2RLFMnMm/iNOtYDA9zsGAvxmiEmspSHowpdF0RMDkWmR6TaXnOnO5O1p4pe1uMVNVXMntHJICEUwkh3M3A4PoYsz2OJUuk7+4vPH94luDUaWxZcaY9Pjy/Z3Dire0bM4sUs2Yp3V0RBnvdGMsKef34Noz5OgobBGx1CtZHegKzBRz/KIG9VqLi2QRm4xKtb7yEhMLR12bS3ccKay6fFw+amdTtoXcoDNz61zO7I0Zrk46YbMQd3UF1YlVC3q5CQtU7+eDQ1jgXJbUdPAzcxGwYp380xmJ4tSkTowkCUYk7N00YlThPmmZIJKCiZJGf+r+ivChKVunWaFSSkuK8nYN3zkCPq5ZjbbkMjcQZ9y4xKDm47IKO85lM9Mt0nJVwLVXhcuvxLWTyfmdN6mewASTVvB1pXu4/DNhtUQ48Y+SVl4sZnbEzOavQNxJhz24zzfVhXj0UwfPIS0meZWXcU+Vz6W3jLwT3qZre/CblhbSIJg0IjQoL2p83FfTDb+t6AS1T/uGWlikqaFJU0KSooElRQZOigiZFBU2KCpoUFZbPPhPL1yTTLrLNAn77EzxibCTPWMAgAAAAAElFTkSuQmCC',
        'in' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAACTUlEQVRoge2ZMWgTYRTH/ydRUyzJdREd0l6HokKEkzoUs5xOdpF2Mt0y1U1ucSktBBQcOqR06aBD3BREs0UQaRwiIsUGWmnBwVgFi9O1IC1dUt7j7szw5u/L8H5w5PuOD/Lux/ve+3Jxjj4/G+ud/JuFkpIhISdbb2qq5D9nBiWQQUKlCKgUAZUioFIEVIqAShFQKQIqRUClCKgUAZUi4PR6vQDAuq0ADqJjfGp1sd35w/OifxnTM1dthcNYzZTl6jpuejW8rG/iVzfie4thExPuUzQbu9bispYpDytvsd3Z56zIu1kUvBEUPJfltFs/8KreweOVuyhXbpgODRnj3whwZvQLoXFyFf1LfM2HU1gK3/F2orlJrGTKpFfDg3CK60ki4/fPCLl8Fjk3i1Lg8edhdMzrV+tmXwwarykkgh6WHjopsiTk2vWLcBzwmCRRBlGGtFtd0yGal/Kts8+1Y9Qbwajnpvfz7hBmysV0Tmto69jAeE1Jiim14L244xAXhs/hS3svndOawzirTGM8U0hKLu429MC0RaiWfGh+x87WXx7fCjxeS+uSsUmsdJ9yxefzyKNqkJ5PCMqcUiyBtg617ReNOePxWTun3PHX+DNpy5QViGvLQXSE5WqLBZnuPLAphTpPcoCjLUTbqhSM8xmGOtL9io8nK9Omw2Ks//YhKc3GDovIxW2YTrGFvs5kGqf2fiF8/fW5/kPYh746EFApAipFQKUIqBQBlSKgUgRUioBKEVApAipFQKUIZM6fHdq4feXex4GLzBYATgFnfdsMp78okgAAAABJRU5ErkJggg==',
        'be' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAAxUlEQVRoge3asQnCYBRF4RtjoRCSFWwEewdwDjtHcxQXECyFLJHCQtBCRO1O+f+FcD547S1O/Zokh+8VN43bbujbW/Hh86PLfiq/+3GZJ1kl2dVYH/q2xmzyrDP7M6s7/5+MAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAowCjAKMAt4vo8ckpxrj1/HebdaL8v+uy6ZLUuePNpletUoR6g9IgaoAAAAASUVORK5CYII=',
        'id' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAAp0lEQVRoge3YsQnCUBhF4Rt5jVYuoKTIABnBNeycIBMKmSWTPAkEQ+D0ecX5Jrgc+Ju/+2a8Jxmjv7IFmU2yu7QypCVGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYB5TG9b9fhuTS37ERdrfXlj/bI8wFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYBRgFGAUYB6zuyT/JpbtlZkvwAvOIMAmHUGqQAAAAASUVORK5CYII=',
        'ge' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAAC1klEQVRoge2ZMW/TUBDHL24okZsIq1JFYCELHVgamgEGRJK5ILp0boYuIFVKGFgB8QESFlgYysbYSnRuysTSEgaQoIBSIZVISMgoLgqUxOhOeZYrPbvNe45dhfeTIsd58Xunv+/u2Xcx27brEAFfcoszfqumH9/9rF+bsSIwrRwHgHwEC0Nn+4PvuP23mwvNmMMYWkQLn2iUKBziMhd3Gh+hVa7S90R2GtK1ynCsPAJzZR3MlZf0J6N0A4zSnNR8UqKgEL8230gZEARjRtKxA0WRRSh80EO6ZvvQbxOFnOfYsNivb9PMmpFyVjiVOUdHNiYCbsn2oNd9nb8H7bVXnuMXNp7ARGHWd473sau+40HMccl+7TvuQVHIU9jdGFWEc0rq1nXKKd8fPqNzPX+ZQmi/vkUxHga4JtrQMy0wn6/TisbiHGhGksJYFKHwccNceOr+Ekw9WBr4Oi+OEz4MzB+7xTt0Nsh1HoiFD8OdUNFDouKg+c1Z+beEhzCktmTo3xnob4tRgZ7B7BgPIN9JiTJmpGRdNRAw8QeZ/NVjPgclCofYO7gitfuMGpPLCxXlKRyUKByUKByUKByUKByUKByUKBxi1sZWJM8p7K3Wi7PVMpUFwkbTEzfjJ+HdhQcKEpFtlgofDkoUDlKlAyzuNAu3ndf2TP3pcKw8gh+1F9T7QVLz+YEqgDykPAXFONhtUc9lPHNeyhAZTmenofN2hz6J7EXp+YQ8pb266fRatDNJ6P20qFiMtdKe2aYkGUbFH70D13GXILumRXagF4t2CoUK13ulR071nMd/2fcZdYTCRy/MktvikT2EYb8Fz9FtgygeHwdsq7CCeatSo2O6WqYjhpEoQqJ4xapst39Q2C7j7hvjzcIdSAap8HH3W/4098LQgYs70XYaO9LzSXcIRQmyQxgwKtHyUKJwUKJwwN2nGMXCk8sLWb9xTU98wtf48CzqA9D4B6Wr6yhQqsQOAAAAAElFTkSuQmCC',
        'gb' => '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',
        'cn' => 'iVBORw0KGgoAAAANSUhEUgAAAEUAAAApCAYAAABju+QIAAAACXBIWXMAAAsSAAALEgHS3X78AAACYElEQVRoge2Yz2sTURDHv/sjJbAaAoViqQpiPXhL7xGv+QNqzxbPNVerJy/qUdG7f4BnsfccPTRnKaQUJVWQFtuApPtD5m02tO7k7RYWzMh8IGzYfVnyPjszb946n2/cbgFoQsnY9wG8BnBflUx57lZ9R+9K+pGMX9V/b9xzjYyg5eLH+wjRaSJWS2kpNOHodPb10W6Mux8X0u/9GFEvsY6fZ0qlDwlZ2bb7W9r08Hsvwdl3uRGSUSpSKDUabdcaLcO3EYAI17d9HH2Kzbmi6JpXSkXK1bY7lVPE15fhdMTylm9+Q1EkiVyk0NOtr16cfGMipdnxMB5eHE/1g4MKbn3VQbPjY/guki2Fwj1Yc7D0MP90g5aDW2/Sn0Qj4OBpmBtzHqoxtWXHSD3pxRgfyqg3bE2hJXW0m+DmCx9ekL8+6ic4eHZmX40mEUTHrMZIYWaRoMl82RjnztPTH3TtQs7fQ5oQFBXaYC1/mdLhMixcu9z4ecAqJSuwxzsxBt3QRAmlU5lViKBCK23lQVGfQgX326twmgKDbpwus20Xv3r2tKB+pdlxJ/epYfA4lF1oMQn7vydCdYT6EIqAItJxNTOWGjspQmCTYpsEpdEssi6Wjkc7sVmK63dk1ZXKd8mL6x72HqWr088PadMmKUpQts0vA+2SV574Jl2onkh+p1KZlMUHnmnqaJdMhVnqawNUmT7UBdMumZbgWfshKVT+OjKVI5vKpfwPqBQGlcKgUhhUCoNKYVApDCqFQaUwqBQGlcKgUhhUCoNKYVApDCqFQaUwqBQGekfbn7t/9S8B9v8ASSzMIuzi9wgAAAAASUVORK5CYII=',
    ];
@endphp

@if($countryStats)
    <style>
        .statistics-countries-wrap{width:100%;max-width:945px;margin:0 auto 78px;padding:0}
        .statistics-countries{width:430px;margin:0 auto}
        .statistics-countries__title{margin:0 0 42px;text-align:center;font:700 20px/1.15 Georgia,"Times New Roman",serif;color:#202020}
        .statistics-countries__row{display:grid;grid-template-columns:34px minmax(0,1fr) 72px;column-gap:11px;align-items:center;height:37px}
        .statistics-countries__flag{display:block;width:34px;height:34px;border-radius:50%;object-fit:cover}
        .statistics-countries__flag--empty{width:34px;height:34px}
        .statistics-countries__name{font:400 14px/1.2 Georgia,"Times New Roman",serif;color:#a6a6a6;white-space:nowrap}
        .statistics-countries__value{font:400 14px/1.2 Georgia,"Times New Roman",serif;color:#161616;text-align:right;white-space:nowrap}
        @media(max-width:520px){
            .statistics-countries-wrap{overflow-x:auto}
            .statistics-countries{width:430px;padding:0 12px}
        }
    </style>

    <div class="statistics-countries-wrap">
        <section class="statistics-countries" aria-labelledby="statisticsCountriesTitle">
            <h2 class="statistics-countries__title" id="statisticsCountriesTitle">{{ $countryStats['title'] }}</h2>

            <div class="statistics-countries__list">
                @foreach($countryStats['items'] as $item)
                    <div class="statistics-countries__row">
                        @if($item['flag'])
                            <img
                                class="statistics-countries__flag"
                                src="data:image/png;base64,{{ $countryFlagData[$item['flag']] }}"
                                alt=""
                                aria-hidden="true"
                            >
                        @else
                            <span class="statistics-countries__flag--empty" aria-hidden="true"></span>
                        @endif

                        <span class="statistics-countries__name">{{ $item['name'] }}</span>
                        <span class="statistics-countries__value">{{ $item['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endif
