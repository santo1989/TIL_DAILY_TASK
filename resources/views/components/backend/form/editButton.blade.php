<button type="submit"
    class="btn btn-sm btn-outline-success">
    <span>
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32">
            <title>Edit</title>
            <path
                d="M12 0v3c2.296 0 4.522 0.449 6.616 1.335 2.024 0.856 3.842 2.082 5.405 3.644s2.788 3.381 3.645 5.405c0.886 2.094 1.335 4.32 1.335 6.616h3c0-11.046-8.954-20-20-20z">
            </path>
            <path d="M12 6v3c2.938 0 5.701 1.144 7.778 3.222s3.222 4.84 3.222 7.778h3c0-7.732-6.268-14-14-14z"></path>
            <path
                d="M15 12l-2 2-7 2-6 13 0.793 0.793 7.275-7.275c-0.044-0.165-0.068-0.339-0.068-0.518 0-1.105 0.895-2 2-2s2 0.895 2 2-0.895 2-2 2c-0.179 0-0.353-0.024-0.518-0.068l-7.275 7.275 0.793 0.793 13-6 2-7 2-2-5-5z">
            </path>
        </svg>

    </span>
</button>

<style>
    .btn-outline-warning:hover {
        background: linear-gradient(45deg, #FCA311, #FFD3B6);
        color: #fff;
    }

    .btn-outline-secondary:hover {
        background: linear-gradient(45deg, #545454, #808080);
        color: #fff;
    }

    .btn-outline-success:hover {
        background: linear-gradient(45deg, #28A745, #6FCE9C);
        color: #fff;
    }

    .btn-outline-danger:hover {
        background: linear-gradient(45deg, #DC3545, #FFA0A0);
        color: #fff;
    }

    .btn-outline-info:hover {
        background: linear-gradient(45deg, #17A2B8, #9ADCEC);
        color: #fff;
    }

    .btn:hover span {
        animation: pulse 1s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }
</style>
