import React, { useState, useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

interface AnimatedCounterProps {
    end: number;
    duration?: number;
    decimals?: number;
    prefix?: string;
    suffix?: string;
}

export default function AnimatedCounter({ end, duration = 2, decimals = 0, prefix = '', suffix = '' }: AnimatedCounterProps) {
    const [count, setCount] = useState(0);
    const elementRef = useRef<HTMLSpanElement>(null);

    useEffect(() => {
        const obj = { value: 0 };
        
        const animation = gsap.to(obj, {
            value: end,
            duration: duration,
            ease: "power2.out",
            scrollTrigger: {
                trigger: elementRef.current,
                start: "top bottom-=50px",
                toggleActions: "play none none none"
            },
            onUpdate: () => {
                setCount(obj.value);
            }
        });

        return () => {
            animation.kill();
            if (animation.scrollTrigger) animation.scrollTrigger.kill();
        };
    }, [end, duration]);

    return (
        <span ref={elementRef}>
            {prefix}
            {count.toLocaleString(undefined, {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals,
            })}
            {suffix}
        </span>
    );
}
