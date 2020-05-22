---
title: Swapping without using a third variable in C++
description: "This program will swap the value of these two numbers without using a temporary variable. The idea behind this concept is very simple. First we will sum the two given numbers and store it in a. Then subtract b from a and store it in b, Again subtract b from a and store it in a. That's all the numbers are swapped"
imgUrl: blog/introducing-smart-prefetching/main.png
date: 2014-07-08
authors:
  - name: Jacob Samro
    avatarUrl: https://pbs.twimg.com/profile_images/1205941721944584192/7kB12_2G_400x400.jpg
    link: https://twitter.com/jacobsamro
tags:
    - cpp
    - swapping
---

## Code Goes here

```
#include <iostream>

using namespace std;

int main(){
    
	int a,b;
	
	cout<<"Enter the value of a : ";
	cin>>a;
	
	cout<<"\nEnter the value of b : ";
	cin>>b;
	
	cout<<"\nThe first value is "<<a;
	cout<<" and the second is "<<b<<endl;
	    
	a=a+b;
	b=a-b;
	a=a-b;
	
	cout<<"===  After Swapping  ===\n";
	cout<<"The first value is "<<a;
	cout<<" and the second is "<<b<<endl;
		
    return 0;

}
```